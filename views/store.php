<?php ?>
<div class="row">
	<div class="alerts"></div>
    <div class="col-md-5 col-md-offset-4">
        <ul class="nav nav-pills nav main-choice-nav">
            <li role="presentation" class="active"><a class="store" id="add" href="#">Добавить</a></li>
            <li role="presentation"><a class="store" id="show" href="#">Посмотреть</a></li>
        </ul>
    </div>
</div>
<br>
<div class="row">
	<div class="well col-md-8 col-md-offset-2">
		<form method="post" action="/select" name="store" id="store">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="form-group">
						<label for="name">Наименование:</label>
						<input required name="name" type="text" class="form-control" id="name" placeholder="Введите название">
					</div>
					<div class="form-group">
						<label for="category">Категория:</label>
						<select name="category[]" id="category">
							<option value="животное">животное</option>
							<option value="корм">корм</option>
							<option value="соп.товар">сопутствующий товар</option>
						</select>
					</div>
					<div class="form-group">
						<label>Подкатегория:</label>
						<div class="checkbox subcat">
							<label>
								<input class="check" name="subcategory[rodent]" type="checkbox" value="1"> грызун
							</label>
							<label>
								<input class="check" name="subcategory[amphibian]" type="checkbox" value="1"> земноводное
							</label>
							<label>
								<input class="check" name="subcategory[reptile]" type="checkbox" value="1"> рептилия
							</label>
							<label>
								<input class="check" name="subcategory[fish]" type="checkbox" value="1"> рыба
							</label>
							<label>
								<input class="check" name="subcategory[cat]" type="checkbox" value="1"> кошка
							</label>
							<label>
								<input class="check" name="subcategory[dog]" type="checkbox" value="1"> собака
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1 col-md-offset-5">
					<button type="submit" class="submit-button btn-lg btn-success">
						Сохранить
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

