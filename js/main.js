Ext.onReady(function() {
	document.getElementById('username').value = readCookie('username');
	Ext.fly('username').on('blur', function(e, t) {
		createCookie('username', document.getElementById('username').value, 30);
	});
	var myJsonReader = new Ext.data.JsonReader({
		root: 'projects',
		fields: ['name', 'label', 'path', 'type']
	});

	var store = new Ext.data.GroupingStore({
		reader: myJsonReader,
		proxy: new Ext.data.HttpProxy({
			url: 'projects.php'
		}),
		groupField:'type',
		sortInfo: {
			field: 'label', direction: 'ASC'
		}
	});

	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [
			{header: 'Name', width: 120, dataIndex: 'label', sortable: true},
			{header: 'Path', width: 180, dataIndex: 'path', sortable: true},
			{header: 'Type', width: 180, dataIndex: 'type', hidden: true}
		],
		view: new Ext.grid.GroupingView({
			forceFit: true,
			groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Projects" : "Project"]})',
			showGroupName: false
		}),
		renderTo: 'projectGrid',
		width: 650,
		height: 300
	});
	grid.on('rowclick', function(grid, rowIndex, e){
		document.getElementById('output').innerHTML = '';
		var project = grid.store.getAt(rowIndex).data;
		var username = document.getElementById('username').value;
		if (username.length < 1) {
			alert('Please enter username first!');
			document.getElementById('username').focus();
			return;
		}
		var templateLines = [
			'git clone --recursive git://git.typo3.org/{projectPath} {projectName}',
			'cd {projectName}',
			'scp -p -P 29418 {username}@review.typo3.org:hooks/commit-msg .git/hooks/',
			'git config remote.origin.pushurl ssh://{username}@review.typo3.org:29418/{projectPath}',
			'git config remote.origin.push HEAD:refs/for/master'
		];
		if (project.type == 'Distribution' || project.type == 'Application') {
			templateLines.push(
				'git submodule foreach \'scp -p -P 29418 {username}@review.typo3.org:hooks/commit-msg .git/hooks/\'',
				'git submodule foreach \'git config remote.origin.pushurl ssh://review.typo3.org:29418/FLOW3/Packages/`basename $path`.git\'',
				'git submodule foreach \'git config remote.origin.push HEAD:refs/for/master\'',
				'git submodule foreach \'git checkout master; git pull\''
			)
		}
		var template = new Ext.Template(
			templateLines.join('\n'),
			{
				compiled: true,
				disableFormats: true
			}
		);
		template.append('output', {projectName: project.name, projectPath: project.path, username: username});
	});

	store.load();
});

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
