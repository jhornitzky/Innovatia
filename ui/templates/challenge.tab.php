<div class="fixed-left">
	<div class="treeMenu" style="margin-bottom: 10px;">
		<div class="itemHolder headBorder">
			<h3>latest challenges and ideas from across the web</h3>
		</div>
	</div>
</div>

<div class="fixed-right">
	<div id="challenge_container">
	</div>
</div>

<script type='text/javascript'>
function grabTweets(user) {
	$.getJSON('http://twitter.com/statuses/user_timeline.json?screen_name=' + user + '&count=10&callback=?', 
		function(data) {
			console.log(data);
			for (var i = 0; i < data.length; i++) {
				var tweet = data[i];
				$('#challenge_container').append('<div class="clearfix"><div class="image"><img src="'+tweet.user.profile_image_url+'"/></div><div class="tag tweet"></div><div class="content"><div class="time">' + tweet.created_at + '</div>' + urlify(tweet.text) + '</div></div>');
			}
		});
}

function urlify(text) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '">' + url + '</a>';
    })
    // or alternatively
    // return text.replace(urlRegex, '<a href="$1">$1</a>')
}

grabTweets('ChallengeFeed');
grabTweets('iinspireus');
</script>