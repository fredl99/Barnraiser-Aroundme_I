<div id="col_left">
    <h1>profile</h1>
    <AM_BLOCK plugin="barnraiser_openid" name="card" />
    <h1>My blog</h1>
    <AM_BLOCK plugin="barnraiser_blog" name="list" limit="10" webpage="blog" />
    <h1>My activity log</h1>
    <AM_BLOCK plugin="barnraiser_connection" name="log" limit="6" />
</div>

<div id="col_middle">
    <h1>My Guestbook</h1>
    <AM_BLOCK plugin="barnraiser_guestbook" name="list" limit="4" />
    <h1>Email me</h1>
    <AM_BLOCK plugin="barnraiser_contact" name="email" />
</div>

<div id="col_right">
     <h1>My connections</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="gallery" limit="16" />
     <h1>incoming</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="inbound_list" limit="5" />
     <h1>outgoing</h1>
     <AM_BLOCK plugin="barnraiser_connection" name="outbound_list" limit="5" />
     <h1>connect to me</h1>
     <AM_BLOCK plugin="barnraiser_openid" name="connect" />
</div>

<div style="clear:both;"></div>

<AM_BLOCK plugin="custom" name="footer" />