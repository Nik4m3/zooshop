<?php ?>
<div class="row results">
<?php
if (!empty($data)) {
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
                    <?php
                    foreach ($tableHeaders as $value) {
                        echo "<th>$value</th>";
                    }
                    echo "<th>Действие</th>";
                    ?>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cleanData as $value) {
                        echo "<tr>";
                        foreach ($value as $key => $meaning) {
                            if (!$meaning) {
                                echo '<td></td>';
                            } elseif ($key != 'id' && $meaning == '1') {
                                echo '<td><span class="glyphicon glyphicon-ok"></span></td>';
                            } else {
                                echo "<td>$meaning</td>";
                            }
                        }
                        echo '<td><button type="button" class="btn-primary sell-btn">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Продать
                            </button></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <!--Кнопка "продать"-->
    <script src="js/sell-button.js"></script>
<?php
} else {
    echo '<div class="not-found"><p>По вашему запросу не найдено ни одного товара</p></div>';
}
?>
</div>
