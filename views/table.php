<?php
	/** @var $tableHeaders array */
	/** @var $data array */
?>
<div class="row results">
	<?php if (!empty($data)): ?>

		<?php
			/* Упорядочить данные для отображения пришедшие из БД, оставить только нужные пары ключ -> значение */
			$cleanData = [];
			foreach ($data as $value) {
				$tempArray = [];
				foreach ($value as $key => $meaning) {
					if (!is_int($key)) {
						$tempArray[$key] = $meaning;
					}
				}
				$cleanData[] = $tempArray;
			}
		?>

		<div id="shop-data">
			<table class="table table-bordered table-hover" id="goods">
				<thead>
				<tr>
					<?php foreach ($tableHeaders as $value): ?>
						<th><?=$value?></th>
					<?php endforeach; ?>
					<th>Действие</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($cleanData as $value): ?>
					<tr>
						<?php foreach ($value as $key => $meaning): ?>
							<?php if (!$meaning): ?>
								<td></td>
							<?php elseif ($key != 'id' && $meaning == '1'): ?>
								<td><span class="glyphicon glyphicon-ok"></span></td>
							<?php else: ?>
								<td><?=$meaning?></td>
							<?php endif; ?>
						<?php endforeach; ?>
						<td>
							<button type="button" class="btn-primary sell-btn">
								<span class="glyphicon glyphicon-shopping-cart"></span> Продать
							</button>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<!--Кнопка "продать"-->
		<script src="js/sell-button.js"></script>

	<?php else: ?>
		<div class="not-found"><p>По вашему запросу не найдено ни одного товара</p></div>
	<?php endif; ?>

</div>
