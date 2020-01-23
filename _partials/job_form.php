<!-- Title -->
<div class="form">
    <h5>Job Title</h5>
    <input class="search-field" type="text" name="title" placeholder="" value="<?php print isset($job["title"]) ? $job["title"]: ''; ?>"/>
</div>

<!-- Location -->
<div class="form">
    <h5>Location <span>(optional)</span></h5>
    <input class="search-field" type="text" name="location" placeholder="e.g. Nairobi, Kenya"
           value="<?php print isset($job["location"]) ? $job["location"]: ''; ?>"/>
    <p class="note">Leave this blank if the location is not important</p>
</div>

<!-- Job Type -->
<div class="form">
    <h5>Job Type</h5>
    <select data-placeholder="Full-Time" name="type" class="chosen-select-no-single">
        <option value="<?php print isset($job["type"]) ? $job["type"] : ''; ?>"><?php print isset($job["type"]) ? $job["type"]: ''; ?></option>
        <option value="Full-Time">Full-Time</option>
        <option value="Part-Time">Part-Time</option>
        <option value="Internship">Internship</option>
        <option value="Freelance">Freelance</option>
    </select>
</div>

<!-- Level -->
<div class="form">
    <h5>Job Level (Optional)</h5>
    <select data-placeholder="Senior" name="level" class="chosen-select-no-single">
        <option value="<?php print isset($job["level"]) ? $job["level"] : ''; ?>"><?php print isset($job["level"]) ? $job["level"]: ''; ?></option>
        <option value="Senior">Senior</option>
        <option value="Middle">Middle</option>
        <option value="Junior">Junior</option>
    </select>
</div>


<!-- Choose Category -->
<div class="form">
    <div class="select">
        <h5>Job Category</h5>
        <select data-placeholder="Choose Categories" name="job_category_id" class="chosen-select">
            <option value="<?php print isset($job["job_category_id"]) ? $job["job_category_id"] : ''; ?>"><?php print isset($job["job_category_id"]) ? $job["job_category_id"]: ''; ?></option>
            <?php foreach ($job_categories as $catg) : ?>
                <option value="<?php echo $catg["id"]; ?>"><?php echo $catg["name"]; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form">
    <h5>Salary (Optional)</h5>
    <input class="search-field" type="number" name="salary" placeholder="" value="<?php print isset($job["salary"]) ? $job["salary"] : ''; ?>"/>
</div>

<!-- Tags -->
<div class="form">
    <h5>Job Tags <span>(optional)</span></h5>
    <input class="search-field" type="text" name="job_tags"
           placeholder="e.g. PHP, Social Media, Management"
           value="<?php print isset($job["salary"]) ? $job["salary"] : ''; ?>"/>
    <p class="note">Comma separate tags, such as required skills or technologies, for this job.</p>
</div>


<!-- Description -->
<div class="form">
    <h5>Description</h5>
    <textarea class="WYSIWYG" name="description" cols="40" rows="3" id="summary"
              spellcheck="true"><?php print isset($job["description"]) ? $job["description"] : ''; ?></textarea>
</div>

<!-- Skills -->
<div class="form">
    <h5>Skills (Optional)</h5>
    <textarea class="WYSIWYG" name="skills" cols="40" rows="3" id="summary"
              spellcheck="true"><?php print isset($job["skills"]) ? $job["skills"] : ''; ?></textarea>
</div>

<!--Optional Skills -->
<div class="form">
    <h5>Optional Skills (Optional)</h5>
    <textarea class="WYSIWYG" name="optional_skills" cols="40" rows="3" id="summary"
              spellcheck="true"><?php print isset($job["optional_skills"]) ? $job["optional_skills"] : ''; ?></textarea>
</div>

<!--Optional Skills -->
<div class="form">
    <h5>Responsibilities (Optional)</h5>
    <textarea class="WYSIWYG" name="responsibilities" cols="40" rows="3" id="summary"
              spellcheck="true"><?php print isset($job["responsibilities"]) ? $job["responsibilities"] : ''; ?></textarea>
</div>

<!-- Application email/url -->
<div class="form">
    <h5>Application email / URL</h5>
    <input type="text" name="application_url" placeholder="Enter an email address or website URL" value="<?php print isset($job["application_url"]) ? $job["application_url"] : ''; ?>">
</div>

<!-- TClosing Date -->
<div class="form">
    <h5>Closing Date <span>(optional)</span></h5>
    <input data-role="date" type="date" name="deadline" value="<?php print isset($job["deadline"]) ? $job["deadline"] : ''; ?>">
    <p class="note">Deadline for new applicants.</p>
</div>