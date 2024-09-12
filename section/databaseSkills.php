
<div class = "skills" id = ""><br>
    <?php
        include_once "tools/functions.php";
        SkilldataHandler::RenderSkillData(Translation::getFileContents("database_skills"), SkilldataHandler::getDatabaseSkillDataDB(1, -1));
    ?>
</div>