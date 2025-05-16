const fs = require("fs");
const path = require("path");
const UglifyJS = require("uglify-js");
const postcss = require('postcss');
const postcssSass = require('@csstools/postcss-sass');
const postcssSCSS = require('postcss-scss');
const postcssCombineMediaQuery = require("./postcss/combine-media-query");
const cssnano = require('cssnano');
const package = require("../../package.json");

// Path to process
const inputJS = "./src/js";
const outputJS = "./dist/js";

const inputCSS = "./src/scss";
const outputCSS = "./dist/css";

const mTimes = {};
const jsImports = {};

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
				const mtime = stats.mtime.getTime();

				if (mTimes[path.join(dirPath, file)] == mtime)
					continue;

				mTimes[path.join(dirPath, file)] = mtime;

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
				const mtime = stats.mtime.getTime();

				if (mTimes[path.join(dirPath, file)] == mtime)
					continue;

				mTimes[path.join(dirPath, file)] = mtime;

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

	const code = fs.readFileSync(sourceFile, "utf8")

	if (code.startsWith("// @import-only"))
		return;

	const imports = code.matchAll(/\/\/[ \t]*@import[ \t]+(?:"(.+?)"|'(.+?)');/gm);

	const files = {};

	for (const i in jsImports)
	{
		jsImports[i] = jsImports[i].filter(x => x != file);
	}

	for (const imp of imports)
	{
		const importSourceFile = path.join(inputJS, imp[1]);
		const importCode = fs.readFileSync(importSourceFile, "utf8");

		files[imp[1]] = importCode;

		if (!jsImports[imp[1]])
			jsImports[imp[1]] = [];

		jsImports[imp[1]].push(file);
	}

	files[file] = code;

	const result = UglifyJS.minify(files,
	{
		mangle:true,
		compress:
		{
			dead_code: true
		},
		sourceMap:
		{
			filename: file,
			url: file + ".map"
		}
	});

	if (result && result.code)
	{
		if (!fs.existsSync(path.dirname(targetFile)))
			fs.mkdirSync(path.dirname(targetFile), { recursive: true });

		fs.writeFileSync(targetFile, result.code);

		const stats = fs.lstatSync(sourceFile);

		const map = JSON.parse(result.map);
		map.sources = [ "../../src/js/" + file + "?v=" + stats.mtime.getTime() ];

		fs.writeFileSync(targetFile + ".map", JSON.stringify(map));

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

	let result = null;

	while (!result)
	{
		try
		{
			result = await postcss(
			[
				postcssSass(),
				postcssCombineMediaQuery,
				cssnano({
					preset: 'default'
				})
			]
		).process(fs.readFileSync(sourceFile), { from: sourceFile, to: targetFile, parser: postcssSCSS });
		}
		catch (e)
		{
			console.error("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[91mError in " + file + "\x1b[0m");
			console.error(e.message);

			const modTime = getFileModificationTime(sourceFile);
			let lastModTime = modTime;
	
			while (lastModTime && lastModTime == modTime)
			{
				await new Promise(resolve => setTimeout(resolve, 1000));
				lastModTime = getFileModificationTime(sourceFile);
			}
		}
	}

	if (result)
	{
		if (!fs.existsSync(path.dirname(targetFile)))
			fs.mkdirSync(path.dirname(targetFile), { recursive: true });

		result.css += "\n/*# sourceMappingURL=" + path.basename(targetFile) + ".map */";

		fs.writeFileSync(targetFile, result.css);
		fs.writeFileSync(targetFile + ".map", result.map?.toString() || "");

		console.log("\x1b[90m[" + (new Date()).toISOString().replace('T', ' ').substring(0, 16) + "] \x1b[92mMinified " + targetFileName + "\x1b[0m");
	}
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

	while (true)
	{
		const filesJS = getJSFiles();

		for (const file of filesJS)
		{
			minifyJS(file);

			for (const relatedFile of jsImports[file] || [])
				minifyJS(relatedFile);
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

		await new Promise(resolve => setTimeout(resolve, 1000));
	}
})();
