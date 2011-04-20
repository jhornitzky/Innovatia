dojo.provide("inno.BigDialog");
dojo.require("dijit.Dialog");
dojo.declare("inno.BigDialog",dijit.Dialog,{
	draggable:false,
	firstPositioned : false,
	_size : function() {
		this._checkIfSingleChild();
		if (this._singleChild) {
			if (this._singleChildOriginalStyle) {
				this._singleChild.domNode.style.cssText = this._singleChildOriginalStyle;
			}
			delete this._singleChildOriginalStyle;
		} else {
			dojo.style(this.containerNode, {
				width : "auto",
				height : "auto"
			});
		}
		var mb = dojo.marginBox(this.domNode);
		var _7 = dijit.getViewport();
		if (mb.w >= _7.w || mb.h >= _7.h) {
			var w = Math.min(mb.w, Math.floor(_7.w * 0.75)), h = Math
					.min(mb.h, Math.floor(_7.h * 0.75));
			if (this._singleChild
					&& this._singleChild.resize) {
				this._singleChildOriginalStyle = this._singleChild.domNode.style.cssText;
				this._singleChild.resize({
					w : w,
					h : h
				});
			} else {
				if (h > $(window).height()) //FIX too large windows 
					h = $(window).height();
				dojo.style(this.containerNode, {
					width : w + "px",
					height : h + "px",
					overflow : "auto",
					position : "relative"
				});
			}
		} else {
			if (this._singleChild
					&& this._singleChild.resize) {
				this._singleChild.resize();
			}
		}
	},
	_position : function() {
		if (!dojo.hasClass(dojo.body(), "dojoMove") && !this.firstPositioned) {
			this.firstPositioned = true;
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
	hide : function() {
		this.firstPositioned = false; //For positioning fix
		var ds = dijit._dialogStack;
		if (!this._alreadyInitialized
				|| this != ds[ds.length - 1]) {
			return;
		}
		if (this._fadeIn.status() == "playing") {
			this._fadeIn.stop();
		}
		ds.pop();
		this._fadeOut.play();
		if (this._scrollConnected) {
			this._scrollConnected = false;
		}
		dojo.forEach(this._modalconnects, dojo.disconnect);
		this._modalconnects = [];
		if (this._relativePosition) {
			delete this._relativePosition;
		}
		this.open = false;
		this.onHide();
	},
});