<div id="col_blog_left">
    <h1>My blog</h1>
    <AM_BLOCK plugin="barnraiser_blog" name="entry" limit="5" />
</div>

<div id="col_blog_right">
    <h1>My card</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="card"  />
    <h1>Connect to me</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="connect"  />
    <h1>My connections</h1>
    <AM_BLOCK plugin="barnraiser_connection" name="gallery" limit="16" />
    <h1>Latest entries</h1>
    <AM_BLOCK plugin="barnraiser_blog" name="list" limit="20" trim="60" />
</div>

<div style="clear:both;"></div>

<AM_BLOCK plugin="custom" name="footer" />