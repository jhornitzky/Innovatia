dojo.provide("inno.Dialog");
dojo.require("dijit.Dialog");
dojo.declare("inno.Dialog",dijit.Dialog,{
	hide:function() {
		this.destroy();
    }
});