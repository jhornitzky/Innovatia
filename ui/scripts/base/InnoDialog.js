dojo.provide("inno.Dialog");
dojo.require("dijit.Dialog");
dojo.declare("inno.Dialog",dijit.Dialog,{
	hide:function() {
		this.destroy();
    },
	_position : function() {
		if (!dojo.hasClass(dojo.body(), "dojoMove")) {
			var _8 = this.domNode, _9 = dijit.getViewport(), p = this._relativePosition, bb = p ? null
					: dojo._getBorderBox(_8), l = Math
					.floor(_9.l
							+ (p ? p.x : (_9.w - bb.w) / 2)), t = Math
					.floor(_9.t
							+ (p ? p.y : (_9.h - bb.h) / 2));
			if (t < 0) // Fix going off screen
				t = 0;
			dojo.style(_8, {
				left : l + "px",
				top : t + "px"
			});
		}
	},
});