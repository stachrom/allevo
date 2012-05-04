YUI.add('toggle-div', function (Y) {

	function togglediv() {
		var content = this.one('.yui3-bd').plug(Y.Plugin.NodeFX, {
			from: { height: 1 },
			to: {
				height: function(node) { 
					return node.get('scrollHeight'); 
				}
			},
			easing: Y.Easing.easeOut,
			duration: 0.5
		});

		var onClick = function(e) {
			this.toggleClass('yui3-closed');
			content.fx.set('reverse', !content.fx.get('reverse')); 
			content.fx.run();
		};

		var control = Y.Node.create('<a title="show/hide content" class="yui3-toggle">' + '<em>toggle</em>' + '</a>');

		this.one('.yui3-hd').appendChild(control);
		control.on('click', onClick);
		
		return content;
	};
	
	// use addMethod to add togglediv to the Node prototype:
	Y.Node.addMethod("togglediv", togglediv);
	// add this functionality to NodeLists:
	Y.NodeList.importMethod(Y.Node.prototype, "togglediv");

}, '1.0.1', {requires: ['anim', 'node']});