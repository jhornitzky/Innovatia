dojo.require("dijit.Dialog");
dojo.require("dijit.form.Button");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.Menu");
dojo.require("dijit.form.ComboBox");
dojo.require("dijit.form.Textarea");
dojo.require("dojo.parser");
dojo.require("dijit.form.TextBox");
dojo.require("dijit.form.DateTextBox");

// CUSTOM CLASSES HERE //////////////////
dojo.provide("inno.Dialog");
dojo.require("dijit.Dialog");
dojo.declare("inno.Dialog",dijit.Dialog,{
	hide:function(){
		this.destroy();
    }
});