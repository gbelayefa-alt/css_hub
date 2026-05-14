<?php
/*
 * Name: Amanda Gbe
 * Date: April 16, 2026
 * Description: Team page with bios and photos of CSS Web Hub creators.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
?>

<div class="team-page"> 
    <h1>Meet the Team</h1>
    <p class="team-subtext">Get to know the talented individuals behind the CSS Web Hub.</p>

    <!-- Team Member -->
    <h2>Computer Science Society Executives</h2>

    <div class="team-card horizontal">
        <img src="../images/scarlett.png" alt="Scarlett">
        <div class="team-info">
            <h3>Scarlettn Cleary</h3>
            <p class="role">President</p>
            <p class="bio"> she/her - Fourth year Computer science student.
                Discord: starltt </p>
        </div>
    </div>

     <div class="team-card horizontal">
        <img src="../images/Sana.png" alt="Sana">
        <div class="team-info">
            <h3>Sana Mohammed</h3>
            <p class="role">Vice President of Student Support</p>
            <p class="bio"> she/her - Fourth year Computer science student.
                Associate Team: Student Support, Discord: sana4237 </p>
        </div>
     </div>

     <div class="team-card horizontal">
        <img src="../images/Catherine.png" alt="Catherine">
        <div class="team-info">
            <h3>Catherine Yu</h3>
            <p class="role">Vice President of Communications</p>
            <p class="bio"> she/her - Third year Computer science student.
                Associate Team: Communications, Discord: micr_wave </p>
        </div>
     </div>

     <div class="team-card horizontal">
        <img src="../images/nathan.png" alt="Nathan">
        <div class="team-info">
            <h3>Nathan Hart</h3>
            <p class="role">Vice President of Events</p>
            <p class="bio"> he/him - Fourth year Computer science student.
                Associate Team: Events, Discord: yallcaps </p>
        </div>
     </div>

     <div class="team-card horizontal">
        <img src="../images/gunveev.png" alt="Guneev">
        <div class="team-info">
            <h3>Guneev Arora</h3>
            <p class="role">Vice President of Outreach</p>
            <p class="bio"> he/him - Fourth year Computer science student.
                Associate Team: Outreach, Discord: gooneev </p>
        </div>
     </div>

     <div class="team-card horizontal">
        <img src="../images/Andrew.png" alt="Andrew">
        <div class="team-info">
            <h3>Andrew Myrden</h3>
            <p class="role">Finance Director</p>
            <p class="bio"> he/him - Fourth year Computer science student.
                Associate Team: Finance, Discord: andrewmyrden </p>
        </div>
     </div>

    <h2>PixelForge Executives</h2>
    <div class="team-card horizontal">
        <img src="../images/amanda.png" alt="Amanda">
        <div class="team-info">
            <h3>Amanda Gbe</h3>
            <p class="role">Co-Founder</p>
            <p class="bio"> she/her - First year Computer science student.
                Outside of coding, she enjoys staying active and is especially passionate about sports. 
            Her favourite sport is soccer. </p>
        </div>
    </div>

      <div class="team-card horizontal">
        <img src="../images/harshini.png" alt="Harshini">
        <div class="team-info">
            <h3>Harshini Lakshman</h3>
            <p class="role">Co-Founder</p>
            <p class="bio"> she/her - First year Computer science student.
                Her favourite subject is physics, and she enjoys tackling complex problems that require analytical thinking. </p>
        </div>
     </div>
     <div class="team-card horizontal">
        <img src="../images/daniel.png" alt="Daniel">
        <div class="team-info">
            <h3>Daniel Yu</h3>
            <p class="role">Co-Founder</p>
            <p class="bio"> he/him - First year Computer science student.
                He enjoys acrylic painting, which contributes to his creative approach to design. </p>
        </div>
     </div>

</div>
<?php require_once '../includes/footer.php'; ?>