<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Entry Rekap SDM</title>
    <link rel="stylesheet" type="text/css" href="../shared/example.css" />
    <script type="text/javascript" src="../../examples/shared/include-ext.js"></script>
    <script type="text/javascript" src="../../examples/shared/options-toolbar.js"></script>
    <script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '../ux/');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.toolbar.Paging',
    'Ext.ux.PreviewPlugin',
    'Ext.ModelManager',
    'Ext.tip.QuickTipManager'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();

    Ext.define('ForumThread', {
        extend: 'Ext.data.Model',
        fields: [
            'title', 'forumtitle', 'forumid', 'username',
            {name: 'replycount', type: 'int'},
            {name: 'lastpost', mapping: 'lastpost', type: 'date', dateFormat: 'timestamp'},
            'lastposter', 'excerpt', 'threadid'
        ],
        idProperty: 'threadid'
    });

    // create the Data Store
    var store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'ForumThread',
        remoteSort: true,
        proxy: {
            // load using script tags for cross domain, if the data in on the same domain as
            // this page, an HttpProxy would be better
            type: 'jsonp',
            url: 'http://www.sencha.com/forum/topics-browse-remote.php',
            reader: {
                root: 'topics',
                totalProperty: 'totalCount'
            },
            // sends single sort as multi parameter
            simpleSortMode: true
        },
        sorters: [{
            property: 'lastpost',
            direction: 'DESC'
        }]
    });

    // pluggable renders
    function renderTopic(value, p, record) {
        return Ext.String.format(
            '<b><a href="http://sencha.com/forum/showthread.php?t={2}" target="_blank">{0}</a></b><a href="http://sencha.com/forum/forumdisplay.php?f={3}" target="_blank">{1} Forum</a>',
            value,
            record.data.forumtitle,
            record.getId(),
            record.data.forumid
        );
    }

    function renderLast(value, p, r) {
        return Ext.String.format('{0}<br/>by {1}', Ext.Date.dateFormat(value, 'M j, Y, g:i a'), r.get('lastposter'));
    }

    var pluginExpanded = true;
    var grid = Ext.create('Ext.grid.Panel', {
        width: 700,
        height: 500,
        title: 'ExtJS.com - Browse Forums',
        store: store,
        disableSelection: true,
        loadMask: true,
        viewConfig: {
            trackOver: false,
            stripeRows: false
        },
        // grid columns
        columns:[{
            // id assigned so we can apply custom css (e.g. .x-grid-cell-topic b { color:#333 })
            // TODO: This poses an issue in subclasses of Grid now because Headers are now Components
            // therefore the id will be registered in the ComponentManager and conflict. Need a way to
            // add additional CSS classes to the rendered cells.
            id: 'topic',
            text: "Topic",
            dataIndex: 'title',
            flex: 1,
            renderer: renderTopic,
            sortable: false
        },{
            text: "Author",
            dataIndex: 'username',
            width: 100,
            hidden: true,
            sortable: true
        },{
            text: "Replies",
            dataIndex: 'replycount',
            width: 70,
            align: 'right',
            sortable: true
        },{
            id: 'last',
            text: "Last Post",
            dataIndex: 'lastpost',
            width: 150,
            renderer: renderLast,
            sortable: true
        }],
        plugins: [{
            ptype: 'preview',
            bodyField: 'excerpt',
            expanded: true,
            pluginId: 'preview'
        }],
        // paging bar on the bottom
        bbar: Ext.create('Ext.PagingToolbar', {
            store: store,
            displayInfo: true,
            displayMsg: 'Displaying topics {0} - {1} of {2}',
            emptyMsg: "No topics to display",
            inputItemWidth: 35,
            items:[
                '-', {
                text: 'Show Preview',
                pressed: pluginExpanded,
                enableToggle: true,
                toggleHandler: function(btn, pressed) {
                    grid.getPlugin('preview').toggleExpanded(pressed);
                }
            }]
        }),
        renderTo: 'topic-grid'
    });

    // trigger the data store load
    store.loadPage(1);
});	
	</script>
    <style>
        .x-grid-cell-headerId-topic b {
            display: block;
        }
        .x-grid-cell-headerId-topic .x-grid-cell-inner {
            white-space: normal;
        }
        .x-grid-cell-headerId-topic a {
            color: #385F95;
            text-decoration: none;
        }
        .x-grid-cell-headerId-topic a:hover {
            text-decoration:underline;
        }
        .x-grid-cell-headerId-topic .x-grid-cell-innerf {
            padding: 5px;
        }
        .x-grid-rowbody {
            padding: 0 5px 5px 5px;
        }
    </style>
</head>
<body>
    <h1>Paging Grid Example</h1>
    <p>This example shows how to create a grid with paging. This grid uses a Ext.data.proxy.JsonP proxy
       to fetch cross-domain remote data (from the Ext forums).</p>
    <p>In addition, the example uses the Preview plugin which enables you to show a configurable preview of a record. This can be toggled on and off in the docked toolbar.</p>
    <p>Note that the js is not minified so it is readable. See <a href="paging.js">paging.js</a>.</p>
    <div id="topic-grid"></div>
</body>
</html>
