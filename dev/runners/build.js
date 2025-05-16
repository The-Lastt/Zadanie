const fs = require("fs");
const path = require("path");
const UglifyJS = require("uglify-js");
const postcss = require('postcss');
const postcssSass = require('@csstools/postcss-sass');
const postcssSCSS = require('postcss-scss');
const postcssCombineMediaQuery = require("./postcss/combine-media-query");
const cssnano = require('cssnano');
const archiver = require("archiver");
const package = require("../../package.json");

// Path to process
const inputJS = "./src/js";
const outputJS = "./dist/js";

const inputCSS = "./src/scss";
const outputCSS = "./dist/css";


function getFileModificationTime(file)
{
	try
	{
		const stats = fs.lstatSync(file);
		return stats.mtime.getTime();
	}
	catch (e)
	{
		return null;
	}
}


function getJSFiles(dirPath = "")
{
	const files = [];

	const dirFiles = fs.readdirSync(path.join(inputJS, dirPath));

	for (const file of dirFiles)
	{
		const stats = fs.lstatSync(path.join(inputJS, dirPath, file));

		if (stats.isDirectory())
			files.push(...getJSFiles(path.join(dirPath, file)));
		else
		{
			if (path.extname(file) == ".js")
			{
				files.push(path.join(dirPath, file));
			}
		}
	}

	return files;
}


function getSCSSFiles(dirPath = "")
{
	const files = [];

	const dirFiles = fs.readdirSync(path.join(inputCSS, dirPath));

	for (const file of dirFiles)
	{
		const stats = fs.lstatSync(path.join(inputCSS, dirPath, file));

		if (stats.isDirectory())
			files.push(...getSCSSFiles(path.join(dirPath, file)));
		else
		{
			if (path.extname(file) == ".scss")
			{
				files.push(path.join(dirPath, file));
			}
		}
	}

	return files;
}


function minifyJS(file)
{
	const sourceFile = path.join(inputJS, file);
	const targetFile = path.join(outputJS, file);

	const code = fs.readFileSync(sourceFile, "utf8");

	if (code.startsWith("// @import-only"))
		return;

	const imports = code.matchAll(/\/\/[ \t]*@import[ \t]+(?:"(.+?)"|'(.+?)');/gm);

	const files = {};

	for (const imp of imports)
	{
		const importSourceFile = path.join(inputJS, imp[1]);
		const importCode = fs.readFileSync(importSourceFile, "utf8");

		files[imp[1]] = importCode;
	}

	files[file] = code;
	
	const result = UglifyJS.minify(files,
	{
		mangle:true,
		compress:
		{
			dead_code: true
		},
		sourceMap: false
	});

	if (result && result.code)
	{
		if (!fs.existsSync(path.dirname(targetFile)))
			fs.mkdirSync(path.dirname(targetFile), { recursive: true });

		fs.writeFileSync(targetFile, result.code);

		console.log("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[92mMinified " + file + "\x1b[0m");
	}
}


async function minifyCSS(file)
{
	let targetFileName = file.replace('.scss', '.css').split('/');
	targetFileName.shift();
	targetFileName = targetFileName.join('/');
	const sourceFile = path.join(inputCSS, file);
	const targetFile = path.join(outputCSS, targetFileName);

	const result = await postcss(
		[
			postcssSass(),
			postcssCombineMediaQuery,
			cssnano({
				preset: 'default'
			})
		]
	).process(fs.readFileSync(sourceFile), { from: sourceFile, to: targetFile, parser: postcssSCSS });

	if (result)
	{
		if (!fs.existsSync(path.dirname(targetFile)))
			fs.mkdirSync(path.dirname(targetFile), { recursive: true });

		fs.writeFileSync(targetFile, result.css);

		console.log("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[92mMinified " + file + "\x1b[0m");
	}
}

function zipDirectory(dirPath, archive, filesToIgnore = [])
{
	const relPath = path.relative(path.resolve("."), path.resolve(dirPath));
	const zipPath = relPath ? package.name + "/" + relPath : package.name;

	const files = fs.readdirSync(dirPath);

	for (const file of files)
	{
		const filePath = dirPath + "/" + file;

		if (filesToIgnore.includes(filePath))
			continue;

		const stats = fs.lstatSync(filePath);

		if (stats.isDirectory())
		{
			zipDirectory(filePath, archive);
		}
		else
		{
			archive.file(filePath, { name: zipPath + "/" + file });
		}
	}
}

async function zipTheme()
{
	const stream = fs.createWriteStream("dist/theme.zip");
	const archive = archiver("zip", { zlib: { level: 9 } });

	const filesToIgnore = fs.readFileSync(".ftpignore", "utf8").split("\n").filter(f => f.trim() != "");
	filesToIgnore.forEach((f, i) => filesToIgnore[i] = "." + f.trim());

	return new Promise((resolve, reject) =>
	{
		archive.on('error', err => reject(err)).pipe(stream);

		zipDirectory(".", archive, filesToIgnore);
	
		stream.on('close', () => resolve());
		archive.finalize();
	});
}

(async () =>
{
	const style = fs.readFileSync("style.css");
	const version = style.toString().match(/Version:\s*([0-9\.]+)/);
	if (version)
	{
		const versionNumber = version[1];
		fs.writeFileSync("style.css", style.toString().replace(versionNumber, package.version));
	}

	if (fs.existsSync(outputJS))
		fs.rmSync(outputJS, { recursive: true });
	if (fs.existsSync(outputCSS))
		fs.rmSync(outputCSS, { recursive: true });
	if (fs.existsSync("dist/theme.zip"))
		fs.rmSync("dist/theme.zip");

	const filesJS = getJSFiles();

	for (const file of filesJS)
	{
		minifyJS(file);
	}

	const filesSCSS = getSCSSFiles();
	const parsedFiles = new Set();

	for (const file of filesSCSS)
	{
		let f = file;
		if (!f.startsWith("styles/"))
			f = "styles/main.scss";
		
		if (parsedFiles.has(f))
			continue;

		parsedFiles.add(f);

		await minifyCSS(f);
	}

	console.log("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[92mZipping theme...\x1b[0m");
	await zipTheme();
	console.log("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[92mDone!\x1b[0m");
})();
