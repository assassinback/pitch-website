<?php

//$result = $db->query('SELECT * FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'users as users ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' ORDER BY views.date ASC', array());
$result = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.viewer IS NOT NULL ORDER BY views.date DESC', array());

$user_views = $result->result_array();

$plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($profile_id));
$planInfo = $plan->row_array();

// views seen
$result = $db->query('UPDATE ' . $dbPrefix . 'views SET new = 0 WHERE viewed = ' . $_SESSION['id'] . '', array());

?>

<?php
// print_r($user_views);
?>

<div class="col-xs-12 col-sm-9 ply_abt">
    <?php foreach ($user_views as $key => $view) { 
        if ($key > 9 || $view['viewer'] == null) { 
            continue;
        }else if($key !== 0 && $view['viewer'] === $user_views[($key -1)]['viewer']){

            $date1 = new DateTime($view['date']);
            $date2 = new DateTime($user_views[($key -1)]['date']);
            $diff = $date1->diff($date2);

            if($diff->i < 5){
                continue;
            }
        }
    ?>

    <div class="row activity">
            <div class="p_profile_img_inn" style="background-image: url(<?= playerImageCheck($planInfo['test_plan_id'] > 1 ? $view['photo']: null) ?>);">
		    </div>
            <div class="col-xs-9 col-sm-9">
                <?php if($planInfo['test_plan_id'] > 1){ ?>
                <div class="row">
                    <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile
                </div>
                <?php } else { ?>
                <div class="row">
                    <p>Someone viewed your profile, upgrade your plan <a href="<?= getLink('profile.php', 'tab=plan'); ?>">here</a> to find out who</p>
                </div>
                <?php } ?>
                <?php if(($planInfo['test_plan_id'] > 1 && ($view['user_type'] == 2 || $view['user_type'] == 3)) && $view['currently_working_for'] != null && $planInfo['test_plan_id'] > 0){ ?>
                <div class="row">
                    <p>Currently works for <?= $view['currently_working_for'] ?></p>
                </div>
                <?php } ?>
                <div class="row">
                    <p><?= time_elapsed_string($view['date']) ?></p>
                </div>
            </div>
    </div>
    <hr>
    <?php } ?>
    <div class="hidden-notifications">
    <?php foreach ($user_views as $key => $view) { 
        if ($key < 9 || $view['viewer'] == null) { 
            continue;
        } else if($key !== 0 && $view['viewer'] === $user_views[($key -1)]['viewer']){

            $date1 = new DateTime($view['date']);
            $date2 = new DateTime($user_views[($key -1)]['date']);
            $diff = $date1->diff($date2);

            if($diff->i < 5 && $diff->days == 0 && $diff->h == 0){
                continue;
            }
        }
    ?>

    <div class="row activity more">
    <div class="p_profile_img_inn" style="background-image: url(<?= playerImageCheck($planInfo['test_plan_id'] > 1 ? $view['photo']: null) ?>);">
		    </div>
            <div class="col-xs-9 col-sm-9">
            <?php if($planInfo['test_plan_id'] > 1){ ?>
                <div class="row">
                    <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile
                </div>
                <?php } else { ?>
                <div class="row">
                    <p>Someone viewed your profile, upgrade your plan <a href="<?= getLink('profile.php', 'tab=plan'); ?>">here</a> to find out who</p>
                </div>
                
                <?php } if(($planInfo['test_plan_id'] > 1 && ($view['user_type'] == 2 || $view['user_type'] == 3)) && $view['currently_working_for'] != null && $planInfo['test_plan_id'] > 0){ ?>
                <div class="row">
                    <p>Currently works for <?= $view['currently_working_for'] ?></p>
                </div>
                <?php } ?>
                <div class="row">
                    <p><?= time_elapsed_string($view['date']) ?></p>
                </div>
            </div>
    </div>
    <hr>
    <?php } ?>
    </div>
    <?php if(count($user_views) > 9){ ?>
    <input type="button" class="load-more" value="Load More">
    <div class="stj_loader loader" style="display: none;">Loading...</div>
    <?php } ?>
</div>
<script>
$('.load-more').on('click', function(e) {
    var hidden = $('.hidden-notifications > .activity:not(.show)');
    console.log(hidden.length);
    for (let index = 0; index < 10; index++) {
        if((hidden.length) > index){
            $(hidden[index]).addClass('show');
        }
    }
    var remain = $('.hidden-notifications > .activity:not(.show)');
    if(remain.length == 0){
        $('.load-more').css('display', 'none');
    }
});
</script>