let selectDemoM1 = new vanillaSelectBox("#demoM1",
{
	"maxHeight": 300,
	"search": true,
	"placeHolder" : "search to load more data",
	"translations": { "all": "everybody", "items": "people" },
	"remote": {
		"onSearch": doSearch, // used fro search and init
		"onInitSize": 10, // if > 0 onSearch is used for init to populate le select element with the {onInitSize} first elements
	}
});

function doSearch(what, datasize)
{
	let valueProperty = "id";
	let textProperty = "name";
	return new Promise(
		function (resolve, reject)
		{
			var xhr = new XMLHttpRequest();
			xhr.overrideMimeType("application/json");
			xhr.open('GET','./data.json', true);
			xhr.onload = function () {
				if (this.status >= 200 && this.status < 300) {
					var data = JSON.parse(xhr.response);
	
					if (what == "" && datasize != undefined && datasize > 0) { // for init to show some data
						data = data.slice(0, datasize);
						data = data.map(function (x) {
							return {
								value: x[valueProperty],
								text: x[textProperty]
							}
						});
					} else {
						data = data.filter(function (x) {
							let name = x[textProperty].toLowerCase();
							if (name.indexOf(what.toLowerCase()) != -1)
								return {
									value: x[valueProperty],
									text: x[textProperty]
								}
						});
					}
					resolve(data);
				} else {
					reject({
						status: this.status,
						statusText: xhr.statusText
					});
				}
			};
			xhr.onerror = function () {
				reject({
					status: this.status,
					statusText: xhr.statusText
				});
			};
			xhr.send();
		});
	}
}
