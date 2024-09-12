<?php
if(!empty(UserHandler::isAdminLogged()))
{
?>
<section id="admin_panel">
    
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h2>Project Management</h2>
                <a href="?page=setupProjects" target="_BLANK">Setup Projects</a><br>
                <h2>User Management</h2>
                <a href="?page=setupUsers" target="_BLANK">Setup Users</a><br>
                <h2>File Management</h2>
                <a href="?page=setupFiles" target="_BLANK">Setup Files</a><br>
                <a href="?page=setupFileaccess" target="_BLANK">Setup File Access</a><br>
                <a href="?page=setupImages" target="_BLANK">Setup Images</a><br>
                <a href="?page=setupVideos" target="_BLANK">Setup Videos</a><br>
                <a href="?page=setupYoutubeVideos" target="_BLANK">Setup YouTube</a><br>
                <h2>Translations Management</h2>
                <a href="?page=setupKeywords" target="_BLANK">Setup Keywords</a><br>
                <a href="?page=setupUrls" target="_BLANK">Setup URLS</a><br>
                <a href="?page=setupTranslations" target="_BLANK">Setup Translation</a><br>
            </div>
            <div class="col-xs-12 col-sm-12">
                
            </div>
        </div>
    </div>
    
</section>
<?php
}else
{
    echo "you need to login for enabling the administration mode";
}
?>