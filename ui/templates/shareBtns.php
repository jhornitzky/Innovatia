<div class="shareBtns" style="margin:0; padding:0;">	
<img src="<?= $serverRoot?>ui/style/social/mail.png" onclick="openMail('yourFriend@theirAddress.com', 'Check out my idea on innoworks', 'I thought you might like my idea. You can see it at <?= $shareUrl ?>')" />
<!-- Tweet -->
<a title="Share on Twitter" href="javascript:(function(){window.twttr=window.twttr||{};var D=550,A=450,C=screen.height,B=screen.width,H=Math.round((B/2)-(D/2)),G=0,F=document,E;if(C&gt;A){G=Math.round((C/2)-(A/2))}window.twttr.shareWin=window.open('http://twitter.com/share','','left='+H+',top='+G+',width='+D+',height='+A+',personalbar=0,toolbar=0,scrollbars=1,resizable=1');E=F.createElement('script');E.src='http://platform.twitter.com/bookmarklets/share.js?v=1';F.getElementsByTagName('head')[0].appendChild(E)}());">
<img src="<?= $serverRoot?>ui/style/social/twitter.png"/>
</a>
<!-- FB -->
<a title="Share on Facebook" href="javascript:var%20d=document,f='http://www.facebook.com/share',l=d.location,e=encodeURIComponent,p='.php?src=bm&v=4&i=1297406954&u='+e(l.href)+'&t='+e(d.title);1;try{if%20(!/^(.*\.)?facebook\.[^.]*$/.test(l.host))throw(0);share_internal_bookmarklet(p)}catch(z)%20{a=function()%20{if%20(!window.open(f+'r'+p,'sharer','toolbar=0,status=0,resizable=1,width=626,height=436'))l.href=f+p};if%20(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}}void(0)">
<img src="<?= $serverRoot?>ui/style/social/facebook.png"/>
</a>
<!-- Del -->
<a title="Share on Delicious" href="javascript:(function(){f='http://www.delicious.com/share?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title)+'&notes='+encodeURIComponent(''+(window.getSelection?window.getSelection():document.getSelection?document.getSelection():document.selection.createRange().text))+'&v=6&';a=function(){if(!window.open(f+'noui=1&jump=doclose','deliciousuiv6','location=yes,links=no,scrollbars=no,toolbar=no,width=550,height=550'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()">
<img src="<?= $serverRoot?>ui/style/social/delicious.png"/>
</a>
<!-- all -->
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4d49f920481fb70f" title="More sharing on AddThis">
<img src="<?= $serverRoot?>ui/style/social/addThis.png"/>
</a>
</div>