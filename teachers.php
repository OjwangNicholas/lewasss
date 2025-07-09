
<?php
// Safe for include: only outputs the teachers section, no HTML/head/body/header/footer
error_reporting(E_ALL);
ini_set('display_errors', 1);
$teachers_file = __DIR__ . '/admin/teachers.json';
if (!file_exists($teachers_file)) {
    $teachers = [];
} else {
    $teachers = json_decode(file_get_contents($teachers_file), true);
    if (!is_array($teachers)) $teachers = [];
}
// Support limiting the number of teachers shown (e.g., for homepage)
$limit = isset($limit) ? intval($limit) : 0;
if ($limit > 0) {
    $teachers = array_slice($teachers, 0, $limit);
}
?>
<div class="container">
    <div class="row justify-content-center mb-5 pb-2">
        <div class="col-md-8 text-center heading-section ftco-animate">
            <h2 class="mb-4"><span>Certified</span> Teachers</h2>
            <p>Meet our dedicated and experienced teaching staff, committed to nurturing every student's potential.</p>
        </div>
    </div>
    <div class="row">
        <?php if (empty($teachers)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No teachers have been added yet. Please check back soon!</div>
            </div>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <div class="col-md-6 col-lg-3 ftco-animate mb-4">
                    <div class="staff" style="border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); background: #fff;">
                        <div class="img-wrap d-flex align-items-stretch">
                            <div class="img align-self-stretch" style="background-image: url(<?php echo htmlspecialchars('admin/' . $teacher['photo']); ?>);"></div>
                        </div>
                        <div class="text pt-3 text-center">
                            <h3><?php echo htmlspecialchars($teacher['name']); ?></h3>
                            <span class="position mb-2"><?php echo htmlspecialchars($teacher['position']); ?></span>
                            <div class="faded">
                                <p><?php echo htmlspecialchars($teacher['bio']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
