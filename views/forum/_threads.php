<?php

use yii\helpers\Html;

?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?php if (!empty($threads)) echo 'Topics'; else echo 'No topics';?></h3>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-hover">
			<?php
				foreach ($threads as $thread) {
					echo "<tr>";
						echo "<td class='col-md-6'>";
							echo Html::a($thread->subject, ['thread/view', 'id' => $thread->id]);
							echo "</br>";
							echo "Started by " . dektrium\user\models\User::find()
								->where([
									'id' => \kriptograf\mforum\models\Post::find()
										->where(['thread_id' => $thread->id])
										->orderBy(['id' => SORT_ASC])
										->one()->author_id
									])
								->one()->username;
							echo ", on " . Yii::$app->formatter->asDatetime(\kriptograf\mforum\models\Post::find()
								->where(['thread_id' => $thread->id])
								->orderBy(['id' => SORT_ASC])
								->one()->created);
						echo "</td>";
						echo "<td class='col-md-2'>";
							echo "Views no: " . $thread->view_count;
							echo "<br/>";
							echo "Replies no: " . \kriptograf\mforum\models\Post::find()
								->where(['thread_id' => $thread->id])
								->count();
						echo "</td>";
						echo "<td class='col-md-4'>";
							echo "Latest post by " . 
								dektrium\user\models\User::find()
									->where([
										'id' => \kriptograf\mforum\models\Post::find()
											->where(['thread_id' => $thread->id])
											->orderBy(['id' => SORT_DESC])
											->one()->author_id
										])
									->one()->username;
							echo "</br>";
							echo "on " . Yii::$app->formatter->asDatetime(\kriptograf\mforum\models\Post::find()
								->where(['thread_id' => $thread->id])
								->orderBy(['id' => SORT_DESC])
								->one()->created);
						echo "</td>";
					echo "</tr>";
				}
			?>
		</table>	
	</div>
</div>