console.log("dev-tools.js loaded");

{
	const styleFiles = {};
	const filesToWatch = {};

	for (const link of document.querySelectorAll("link[rel='stylesheet']"))
	{
		if (link.id.slice(-4) == "-css")
			filesToWatch[link.id.slice(0, -4)] = link.href.split("?")[0];
	}

	(async () =>
	{
		while (true)
		{
			const versions = await (await fetch("/wp-json/proadax/v1/px_dev_stylesheet_version",
			{
				method: "POST",
				headers:
				{
					"Content-Type": "application/json",
				},
				body: JSON.stringify(filesToWatch),
			}
			)).json();
	
			for (const file of versions)
			{
				if (file.type == "style")
				{
					if (!(file.name in styleFiles))
					{
						styleFiles[file.name] = document.querySelector(`link[rel='stylesheet']#${ file.name }-css`);
					}
					if (styleFiles[file.name])
					{
						if (styleFiles[file.name].attributes.href.value == file.url + "?ver=" + file.version)
							continue;

						console.log(`Updated ${ file.name } : ${ file.version }`);

						const link = document.createElement("link");
						link.rel = "stylesheet";
						link.type = "text/css";
						link.media = "all";

						await new Promise(resolve =>
						{
							link.addEventListener("load", () =>
							{
								styleFiles[file.name].remove();
								link.id = file.name + "-css";
								styleFiles[file.name] = link;
								resolve();
							});

							link.addEventListener("error", () => resolve());
	
							styleFiles[file.name].parentNode.insertBefore(link, styleFiles[file.name]);
							link.href = file.url + "?ver=" + file.version;
						});
					}
				}
			}

			await new Promise(resolve => setTimeout(resolve, 1000));
		}
		
	})();
}
