/**
 * @package info.ajaxplorer.plugins
 * 
 * Copyright 2007-2009 Charles du Jeu
 * This file is part of AjaXplorer.
 * The latest code can be found at http://www.ajaxplorer.info/
 * 
 * This program is published under the LGPL Gnu Lesser General Public License.
 * You should have received a copy of the license along with AjaXplorer.
 * 
 * The main conditions are as follow : 
 * You must conspicuously and appropriately publish on each copy distributed 
 * an appropriate copyright notice and disclaimer of warranty and keep intact 
 * all the notices that refer to this License and to the absence of any warranty; 
 * and give any other recipients of the Program a copy of the GNU Lesser General 
 * Public License along with the Program. 
 * 
 * If you modify your copy or copies of the library or any portion of it, you may 
 * distribute the resulting library provided you do so under the GNU Lesser 
 * General Public License. However, programs that link to the library may be 
 * licensed under terms of your choice, so long as the library itself can be changed. 
 * Any translation of the GNU Lesser General Public License must be accompanied by the 
 * GNU Lesser General Public License.
 * 
 * If you copy or distribute the program, you must accompany it with the complete 
 * corresponding machine-readable source code or with a written offer, valid for at 
 * least three years, to furnish the complete corresponding machine-readable source code. 
 * 
 * Any of the above conditions can be waived if you get permission from the copyright holder.
 * AjaXplorer is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * Description : A dynamic panel displaying details on the current file.
 */
PropertyPanel = Class.create({

	initialize: function(userSelection, htmlElement){
				
		this.rights = ['4', '2', '1'];
		this.accessors = ['u', 'g', 'a'];
		this.accessLabels = [MessageHash[288], MessageHash[289], MessageHash[290]];
		this.rightsLabels = ['r', 'w', 'x'];

		this.htmlElement = $(htmlElement).select("[id='properties_box']")[0];
		
		
		if(userSelection.isUnique()){
			this.origValue = userSelection.getUniqueItem().getAttribute('file_perms');
		}else{
			this.origValue = '';
		}
		/*
		document.getElementById('properties_box').innerHTML = '<input type=radio name=recur_apply_to id="file_all" value="0766">'+ MessageHash[515];
		
		if(this.origValue == document.getElementById('file_all').value)
		{
			document.getElementById('file_all').checked = true;
		}*/
		//Ãß°¡
		this.file_owner = userSelection.getUniqueItem().getAttribute('file_owner');
		
		if (this.file_owner == ajaxplorer.user.uid)
		{
			this.permission = 1;
		}
		else
		{
			this.permission = 0;
		}
		if ((ajaxplorer.user.id == 'admin') && (this.file_owner != '0'))
		{
			this.permission = 1;
		}
		//console.debug("file_owner:"+this.file_owner+" ajax.uid:"+ajaxplorer.user.uid+" permission:"+this.permission);
		this.createChmodForm();
		
		this.valueInput = new Element('input', {value:this.origValue, name:'chmod_value'}).setStyle({width:'76px', marginLeft:'55px'});
		this.valueInput.observe((Prototype.Browser.IE?'change':'input'), function(e){
			this.updateBoxesFromValue(this.valueInput.value);
		}.bind(this));
		this.updateBoxesFromValue(this.valueInput.value);		
		this.htmlElement.insert(this.valueInput);
		/**/
		this.valueInput.hide();	//textbox ¼û±è Ãß°¡
		if(userSelection.hasDir()){
			this.createRecursiveBoxDir();
			if(this.origValue == document.getElementById('0711').value)
			{
				document.getElementById('0711').checked = true;
			}
			if(this.origValue == document.getElementById('0755').value)
			{
				document.getElementById('0755').checked = true;
			}
			if(this.origValue == document.getElementById('0777').value)
			{
				document.getElementById('0777').checked = true;
			}
		}
		else
		{
			this.createRecursiveBoxFile();
			if((this.origValue == document.getElementById('0700').value) || (this.origValue == '0711'))
			{
				document.getElementById('0700').checked = true;
			}
			if((this.origValue == document.getElementById('0744').value) || (this.origValue == '0755'))
			{
				document.getElementById('0744').checked = true;
			}
			if((this.origValue == document.getElementById('0766').value) || (this.origValue == '0666') || (this.origValue == '0777'))	//0666 - default permission of uploaded file using ftp
			{
				document.getElementById('0766').checked = true;
			}
		}
		
		
		var recuDiv = new Element('div', {style:'padding-top:8px;'});
	
		if ((ajaxplorer.user.id == 'admin') && (this.file_owner != '0'))
		{
			var recurBox = new Element('input', {type:'checkbox', name:'recursive2', id:'recursive2', checked:'yes'}).setStyle({width:'25px',borderWidth:'0'});
		}
		else{
			var recurBox = new Element('input', {type:'checkbox', name:'recursive2', id:'recursive2', disabled:'true'}).setStyle({width:'25px',borderWidth:'0'});
		}
		
		
		if(userSelection.hasDir())
		{
			recuDiv.insert(recurBox);
			recuDiv.insert(MessageHash[291]);
			this.htmlElement.insert(recuDiv);
			if((ajaxplorer.user.id == 'admin') && (this.file_owner != '0')){
				document.getElementById('recursive2').checked = true;
			}
			
		}
		else
		{
			;
		}
	},
	
	valueChanged : function(){
		return (this.origValue != this.valueInput.value);
	},
	
	createChmodForm : function(){
		this.checks = $H({});
		var chmodDiv = new Element('div').setStyle({width: '140px'});
		// Header Line
		var emptyLabel = new Element('div').setStyle({cssFloat:'left',width:'50px', height:'16px'});
		chmodDiv.insert(emptyLabel);
		for(var j=0;j<3;j++){
			chmodDiv.insert(new Element('div').update(this.rightsLabels[j]+'&nbsp;&nbsp;').setStyle({cssFloat:'left',width:'30px', textAlign:'center'}));
		}
		//chmodDiv.insert('<br>');
		// Boxes lines
		for(var i=0;i<3;i++){
			var label = new Element('div').setStyle({cssFloat:'left',width:'50px', height:'16px', textAlign:'right'});
			label.insert(this.accessLabels[i]);
			chmodDiv.insert(label);
			for(var j=0;j<3;j++){
				var check = this.createCheckBox(this.accessors[i], this.rights[j]);
				chmodDiv.insert(check);
			}
			
		}
		chmodDiv.hide();	//Ãß°¡
		this.htmlElement.insert(chmodDiv);
	},
	
	createCheckBox : function(accessor, right){
		var box = new Element('input', {type:'checkbox', id:accessor+'_'+right}).setStyle({width:'25px',borderWidth:'0'});
		var div = new Element('div').insert(box).setStyle({cssFloat:'left',width:'30px'});
		box.observe('click', function(e){
			this.updateValueFromBoxes();
		}.bind(this));
		this.checks.set(accessor+'_'+right, box);
		return div;
	},
	
	createRecursiveBoxDir : function(){
		var recuDiv = new Element('div', {style:'padding-top:8px;'});
		var recurBox = new Element('input', {type:'checkbox', name:'recursive', checked:'yes'}).setStyle({width:'25px',borderWidth:'0'});
		
		//recuDiv.insert(recurBox);
		//recuDiv.insert(MessageHash[291]);
		this.htmlElement.insert(recuDiv);
		
		//var choices = { "both":"Both", "file":"Files", "dir":"Folders"};
		var choices = { "0711":MessageHash[515], "0755":MessageHash[516], "0777":MessageHash[517]};
		var choicesDiv = new Element('div');
		recuDiv.insert(choicesDiv);
		for(var key in choices){
			var choiceDiv = new Element('div', {style:'padding-left:5px'});
			if(this.permission){
				var choiceDivBox = new Element('input', {
				type:'radio',
				name:'recur_apply_to',
				value:key,
				id:key,
				style:'width:25px;border:0;'
				});
			}
			else
			{
				var choiceDivBox = new Element('input', {
				type:'radio',
				name:'recur_apply_to',
				value:key,
				disabled:'true',
				id:key,
				style:'width:25px;border:0;'
				});
			}
			
			choiceDiv.insert(choiceDivBox);
			
			//if(key=='711'){
			//console.debug(this.origValue);
			if(this.origValue == key){
				choiceDivBox.checked = true;
			}
			choiceDiv.insert(choices[key]);
			choicesDiv.insert(choiceDiv); 
		}
		/*choicesDiv.hide();
		
		recurBox.observe('click', function(e){
			if(recurBox.checked) choicesDiv.show();
			else choicesDiv.hide();
			modal.refreshDialogAppearance();
		});*/
		
	},
	
	createRecursiveBoxFile : function(){
		var recuDiv = new Element('div', {style:'padding-top:8px;'});
		var recurBox = new Element('input', {type:'checkbox', name:'recursive', checked:'yes'}).setStyle({width:'25px',borderWidth:'0'});
		
		//recuDiv.insert(recurBox);
		//recuDiv.insert(MessageHash[291]);
		this.htmlElement.insert(recuDiv);
		
		//var choices = { "both":"Both", "file":"Files", "dir":"Folders"};
		var choices = { "0700":MessageHash[515], "0744":MessageHash[516], "0766":MessageHash[517]};
		
		var choicesDiv = new Element('div');
		recuDiv.insert(choicesDiv);
		for(var key in choices){
			var choiceDiv = new Element('div', {style:'padding-left:5px'});
			if(this.permission){
				if(this.origValue == key)
				{
					var choiceDivBox = new Element('input', {
					type:'radio',
					name:'recur_apply_to',
					value:key,
					CHECKED:'true',
					id:key,
					style:'width:25px;border:0;'
					});
				}
				else
				{
					var choiceDivBox = new Element('input', {
					type:'radio',
					name:'recur_apply_to',
					value:key,
					id:key,
					style:'width:25px;border:0;'
					});
				}
				
			}
			else
			{
				if(this.origValue == key)
				{
					var choiceDivBox = new Element('input', {
					type:'radio',
					name:'recur_apply_to',
					value:key,
					CHECKED:'true',
					disabled:'true',
					id:key,
					style:'width:25px;border:0;'
					});
				}
				else
				{
					var choiceDivBox = new Element('input', {
					type:'radio',
					name:'recur_apply_to',
					value:key,
					disabled:'true',
					id:key,
					style:'width:25px;border:0;'
					});
				}
				
			}
			
			choiceDiv.insert(choiceDivBox);
			
			//if(key=='711'){
			//console.debug(this.origValue);
			/**/if(this.origValue == key){
				choiceDivBox.checked = true;
				//document.getElementById(key).checked = true;
				//choiceDivBox.setAttribute('checked','true');
			}
			
			choiceDiv.insert(choices[key]);
			choicesDiv.insert(choiceDiv); 
		}
		/*choicesDiv.hide();
		
		recurBox.observe('click', function(e){
			if(recurBox.checked) choicesDiv.show();
			else choicesDiv.hide();
			
		});*/
		
	},
	
	updateValueFromBoxes : function(){
		var value = '0';
		for(var i=0; i<3;i++){
			value = value + this.updateValueForAccessor(this.accessors[i]);
		}
		this.valueInput.value = value;
	},
	
	updateValueForAccessor : function(accessor){
		var value = 0;
		for(var i=0;i<3;i++){
			value += (this.checks.get(accessor+'_'+this.rights[i]).checked?parseInt(this.rights[i]):0);
		}
		return value;
	},
	
	updateBoxesFromValue : function(value){
		if(value.length != 4 )return;
		for(var i=0;i<3;i++){
			this.valueToBoxes(parseInt(value.charAt(i+1)), this.accessors[i]);
		}
	},
	
	valueToBoxes : function(value, accessor){				
		for(var i=0;i<3;i++){
			this.checks.get(accessor+'_'+this.rights[i]).checked = false;
		}
		if(value == 0) return;
		var toCheck = $A([]);
		switch(value){
			case 1: 
				toCheck.push('1');
				break;
			case 2: 
				toCheck.push('2');
				break;
			case 3: 
				toCheck.push('1');
				toCheck.push('2');
				break;
			case 4: 
				toCheck.push('4');
				break;
			case 5: 
				toCheck.push('4');
				toCheck.push('1');
				break;
			case 6: 
				toCheck.push('4');
				toCheck.push('2');
				break;
			case 7: 
				toCheck.push('2');
				toCheck.push('4');
				toCheck.push('1');
				break;			
		}
		toCheck.each(function(ch){
			this.checks.get(accessor+'_'+ch).checked = true;
		}.bind(this));
	}
	
});