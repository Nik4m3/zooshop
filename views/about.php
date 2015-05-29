<?php ?>
<div class="about">
    <p>В зоомагазине действуют две системы классификации имеющихся товаров:</p>
    <ul>
        <li>
            <span class="label label-primary">животные</span>
            <span class="label label-primary">корма</span>
            <span class="label label-primary">сопутствующие товары</span>
        </li>
        <li>
            <span class="label label-default">грызуны</span>
            <span class="label label-default">земноводные</span>
            <span class="label label-default">рептилии</span>
            <span class="label label-default">рыбы</span>
            <span class="label label-default">кошки</span>
            <span class="label label-default">собаки</span>
        </li>
    </ul>
    <p>Каждый поступающий на склад товар получает маркер в обоих классификациях.</p>
    <p>Например:</p>
    <ul>
        <li>
            Корм для черепах: <span class="label label-primary">корма</span>
            <span class="label label-default">земноводные</span>
        </li>
        <li>
            Переноска для кошек: <span class="label label-primary">сопутствующие товары</span>
            <span class="label label-default">кошки</span>
        </li>
    </ul>
    <p>При этом в первой классификации товар имеет ровно 1 маркер, а во второй допустимо присвоение нескольких позиций.</p>
    <p>Например:</p>
    <ul>
        <li>
            Ошейник: <span class="label label-primary">сопутствующие товары</span>
            <span class="label label-default">кошки</span>
            <span class="label label-default">собаки</span>
        </li>
    </ul>
    <p>Необходимо разработать программу, позволяющую делать следующее:</p>
    <ul>
        <li>Заносить полученные товары</li>
        <li>Присваивать им маркеры в каждой из классификаций</li>
        <li>Проводить продажу товара (удаление из базы склада)</li>
        <li>Предоставлять отчеты о имеющихся товарах по каждому маркеру классификаций или их сочетанию</li>
    </ul>
</div>