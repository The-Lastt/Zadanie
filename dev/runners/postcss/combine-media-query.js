const postcss = require('postcss');

const plugin = () =>
{
	let atRules = {};

	function addToAtRules(atRule) {
		const key = atRule.params;

		if (!atRules[key]) {
			atRules[key] = postcss.atRule({ name: atRule.name, params: atRule.params });
		}
		atRule.nodes.forEach(node => {
			atRules[key].append(node.clone());
		});

		atRule.remove();
	}

	return {
		postcssPlugin: 'postcss-combine-media-query',
		Once (root)
		{
	
			root.walkAtRules('media', atRule =>
			{
				addToAtRules(atRule);
			});
	
			const rulesList = Object.keys(atRules);
	
			rulesList.sort((a, b) =>
			{
				const aParts = a.split(/(?:and|or)/);
				const bParts = b.split(/(?:and|or)/);
		
				for (const i in aParts)
				{
					if (bParts.length <= i)
						return -1;
		
					const partA = aParts[i].replace(/[\(\)]/g, "").split(":");
					const partB = bParts[i].replace(/[\(\)]/g, "").split(":");
		
					partA.forEach((v, i) => partA[i] = v.trim());
					partB.forEach((v, i) => partB[i] = v.trim());
		
					if (partA[0] != partB[0])
						return partA[0] > partB[0] ? 1 : -1;
					
					if (partA.length == 1 && partB.length == 1)
						continue;
		
					if (partA[1] != partB[1])
					{
						if (partA[0] == "min-width")
							return parseInt(partA[1]) - parseInt(partB[1]);
						else
							return parseInt(partB[1]) - parseInt(partA[1]);
					}
				}
		
				return 0;
			});
	
			for (const mq of rulesList)
			{
				root.append(atRules[mq]);
			}
	
			atRules = {};
		}
	};
};

plugin.postcss = true;

module.exports = plugin;
