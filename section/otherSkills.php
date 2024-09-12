
<div class = "skills" id = ""><br>

    

    <?php
    include_once "tools/functions.php";

    SkilldataHandler::RenderSkillData(Translation::getFileContents("other_skills"),SkilldataHandler::getOtherSkillDataDB(1, -1));
Translation::getFileContents("other_skills")
    ?>
    
</div>