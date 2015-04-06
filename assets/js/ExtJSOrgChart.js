/**
 * Converted into Ext JS by : Shariq Shaikh
 *
 * http://twitter.com/shaikhmshariq
 * Adopted from jQuery org-chart/tree plugin (https://github.com/wesnolte/ExtJSOrgChart).
 *
 * Author: Wes Nolte
 * http://twitter.com/wesnolte 
 *
 * Based on the work of Mark Lee
 * http://www.capricasoftware.co.uk 
 *
 * Copyright (c) 2011 Wesley Nolte
 * Dual licensed under the MIT and GPL licenses.
 *
 */
 Ext.namespace('ExtJSOrgChart');
//structure to hold node details 
ExtJSOrgChart.createNode = function (id,markup,parentId){
	this.parentId=parentId;
	this.id=id;
	this.markup=markup;
	this.child= new Array();
	
	this.getId=getId;
	function getId(){
		return this.id;
	}
	this.setId=setId;
	function setId(id){
		this.id=id;
	}
	
	this.getMarkup=getMarkup;
	function getMarkup(){
		return this.markup;
	}
	this.setMarkup=setMarkup;
	function setMarkup(markup){
		this.markup=markup;
	}
	
	this.getChildNodes=getChildNodes;
	function getChildNodes(){
		return this.child;
	}
	this.setChildNodes=setChildNodes;
	function setChildNodes(child){
		this.child=child;
	}
	this.getParentId=getParentId;
	function getParentId(){
		return this.parentId;
	}
	this.setParentId=setParentId;
	function setParentId(parentId){
		this.parentId=parentId;
	}
	this.addChild=addChild;
	function addChild(childElem){
		this.child.push(childElem);
		return this;
	}
	this.hasChildNodes=hasChildNodes;
	function hasChildNodes(){
		return this.child.length > 0;
	}
}
ExtJSOrgChart.buildNode= function (node, appendTo, level, opts) {
    var tableObj = Ext.DomHelper.append(appendTo, "<table cellpadding='0' cellspacing='0' border='1'/>");
    var tbodyObj = Ext.DomHelper.append(tableObj, "<tbody/>");

    // Construct the node container(s)
    var nodeRow = Ext.get(Ext.DomHelper.append(tbodyObj, "<tr/>")).addClass("node-cells");
    var nodeCell = Ext.get(Ext.DomHelper.append(nodeRow, "<td colspan='2' />")).addClass("node-cell");
	
    var childNodes = node.getChildNodes();
    var nodeDiv;

    if (childNodes.length > 1) {
        nodeCell.dom.setAttribute('colspan', childNodes.length * 2);
    }
    // Draw the node
    var nodeContent=node.getMarkup();
    nodeDiv = Ext.get(Ext.DomHelper.append(nodeCell,"<div>")).addClass("node");
	
    nodeDiv.dom.innerHTML=nodeContent;
    
    if (childNodes.length > 0) {
        // recurse until leaves found (-1) or to the level specified
        if (opts.depth == -1 || (level + 1 < opts.depth)) {
            var downLineRow = Ext.DomHelper.append(tbodyObj,"<tr/>");
            var downLineCell = Ext.DomHelper.append(downLineRow,"<td/>");
			downLineCell.setAttribute('colspan',childNodes.length * 2);
            // draw the connecting line from the parent node to the horizontal line 
            downLine = Ext.get(Ext.DomHelper.append(downLineCell,"<div></div>")).addClass("line down");
	
			// Draw the horizontal lines
            var linesRow = Ext.DomHelper.append(tbodyObj,"<tr/>");
            Ext.each(childNodes,function (item,index) {
                var left = Ext.get(Ext.DomHelper.append(linesRow,"<td>&nbsp;</td>")).addClass("line left top");
                var right = Ext.get(Ext.DomHelper.append(linesRow,"<td>&nbsp;</td>")).addClass("line right top");
            });

            // horizontal line shouldn't extend beyond the first and last child branches
            Ext.select("td:first",false,linesRow).removeClass("top");
            Ext.select("td:last",false,linesRow).removeClass("top");
                

            var childNodesRow = Ext.DomHelper.append(tbodyObj,"<tr/>");
            Ext.each(childNodes,function (item,index) {
                var td = Ext.DomHelper.append(childNodesRow,"<td class='node-container'/>");
				td.setAttribute('colspan',2);
                // recurse through children lists and items
				ExtJSOrgChart.buildNode(item, td, level + 1, opts);
            });

        }
    }


    /* Prevent trees collapsing if a link inside a node is clicked */
    Ext.each(Ext.select('a',true,nodeDiv.dom),function(item,index){
		item.onClick= function(e){
			console.log(e);
			e.stopPropagation();
		}
	});
}
ExtJSOrgChart.prepareTree= function(options){
	var appendTo = Ext.get(options.chartElement);
	var container = Ext.get(Ext.DomHelper.append(appendTo, '<div align="center" class="ExtJSOrgChart"></div>'));
	ExtJSOrgChart.buildNode(options.rootObject,container,0,options);
}
Ext.QuickTips.init();
