<div class = "skills" id = ""><br>
    <?php
    include_once "tools/functions.php";

    SkilldataHandler::RenderSkillData(Translation::getFileContents("programming_skills"),SkilldataHandler::getProgrammingSkillDataDB(1, -1));

    ?>
    
</div>