Tracker={
	make:function() {
		Tracker.update();
		setInterval(function() {
			Tracker.update();
		}, 1000 * 10);
	},
	update:function() {
		$("#content").load("/api/table/tracking/" + page);
	}
}
Tracker.make();