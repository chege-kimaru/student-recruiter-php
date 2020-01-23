<a href="job-page-alt.php?jobId=<?php print $job['id']; ?>" class="listing <?php print $job['type']; ?>">
    <div class="listing-logo">
        <img src="images/job-list-logo-01.png" alt="">
    </div>
    <div class="listing-title">
        <h4><?php print $job['title']; ?> <span class="listing-type"> <?php print $job['type']; ?></span></h4>
        <ul class="listing-icons">
            <li><i class="ln ln-icon-Management"></i> <?php print $job['name']; ?></li>
            <li><i class="ln ln-icon-Map2"></i> <?php print $job['location']; ?></li>
            <li><i class="ln ln-icon-Money-2"></i> <?php print $job['salary']; ?></li>
            <li><div class="listing-date new"> <?php print $job['created_at']; ?></div></li>
        </ul>
    </div>
</a>