<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .custom-table div {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            padding: 10px 12px;

        }

        .custom-table {
            cursor: pointer;
            border-color: #ececec;
            border-bottom: 1px solid #dbdbdb;
        }

        .custom-table:hover {
            background-color: #f6f5f3;
        }

        .custom-table i {
            border: solid 1px;
            border-color: #EAEAEA;
            color: black;
            padding: 2px;
            font-size: 12px;
            margin-right: 10px;
        }

        .custom-row-table {
            background: #faf8f5;
            color: #999;
            padding: 12px 12px 36px;
            border: 1px solid #ececec;
            border-left: none;
            border-right: none;
        }

        .border-right {
            border-right: 1px solid #ececec;;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <form action="/" method="get">
        <div class="row">
            <div class="col-md-2 form-group">
                <label for="start_date">Начальная дата</label>
                <input class="form-control" type="date" id="start_date" name="start_date">
            </div>
            <div class="col-md-2 form-group">
                <label for="end_date">Конечная дата</label>
                <input class="form-control" type="date" id="end_date" name="end_date">
            </div>
            <div class="col-md-2 form-group d-flex align-items-end">
                <button class="btn btn-primary" type="submit">Показать</button>
            </div>
        </div>
    </form>
    <div>
        <div class="row custom-row-table">
            <div class="col-md-4 border-right">Источник</div>
            <div class="col-md-2 border-right">Канал 1</div>
            <div class="col-md-2 border-right">Канал 2</div>
            <div class="col-md-3 border-right">Канал 3</div>
        </div>
        <?php foreach ($data as $key => $datum): ?>
        <?php if ($datum['count'] == 0) {
                    continue;
            }
            ?>
            <?php $hash = salt(8) ?>
            <div class="accordion" id="accordion<?= $hash ?>">
                <div class="row custom-table">
                    <div class="col-md-4 border-right">
                        <span type="span" class="plus-minus collapsed" data-toggle="collapse"
                              data-target="#collapse<?= $hash ?>"><i class="fa fa-plus"></i> <?= empty(trim($key))?'Без метки':$key ?>
                        </span>
                    </div>
                    <div class="col-md-1"><?= $datum['count'] ?></div>
                    <div class="col-md-1 border-right"><?= $datum['percent'] ?>%</div>
                    <div class="col-md-1"><?= $datum['channel_2']['count'] ?></div>
                    <div class="col-md-1 border-right"><?= percent($datum['channel_2']['count'], $datum['count']) ?>%
                    </div>
                    <div class="col-md-1"><?= $datum['channel_3']['count'] ?></div>
                    <div class="col-md-1"><?= percent($datum['channel_3']['count'], $datum['channel_2']['count']) ?>%
                    </div>
                    <div class="col-md-1 border-right"><?= percent($datum['channel_3']['count'], $datum['count']) ?>%
                    </div>
                </div>
                <div id="collapse<?= $hash ?>" class="collapse" aria-labelledby="heading<?= $hash ?>"
                     data-parent="#accordion<?= $hash ?>">
                    <?php $i = 0; ?>
                    <?php foreach ($datum['mediums'] as $med => $medium): ?>
                        <?php $hash = salt(8) ?>
                        <div class="accordion" id="acc<?= $hash ?>">
                            <div class="row custom-table">
                                <div class="col-md-4 border-right">
                                    <span type="span" class="plus-minus collapsed ml-2"
                                          data-toggle="collapse" data-target="#collapse<?= $hash ?>"><i
                                                class="fa fa-plus"></i>
                                        <?= $med ?>
                                    </span>
                                </div>
                                <div class="col-md-1"><?= $medium['count'] ?></div>
                                <div class="col-md-1 border-right"><?= $medium['percent'] ?>%</div>
                                <div class="col-md-1"><?= $medium['channel_2']['count'] ?></div>
                                <div class="col-md-1 border-right"><?= percent($medium['channel_2']['count'], $medium['count']) ?>
                                    %
                                </div>
                                <div class="col-md-1"><?= $medium['channel_3']['count'] ?></div>
                                <div class="col-md-1"><?= percent($medium['channel_3']['count'], $medium['channel_2']['count']) ?>
                                    %
                                </div>
                                <div class="col-md-1 border-right"><?= percent($medium['channel_3']['count'], $medium['count']) ?>
                                    %
                                </div>
                            </div>
                            <div id="collapse<?= $hash ?>" class="collapse" aria-labelledby="heading<?= $hash ?>"
                                 data-parent="#acc<?= $hash ?>">
                                <?php $i = 0; ?>
                                <?php foreach ($medium['campaigns'] as $cam => $campaign): ?>
                                    <?php $hash = salt(8) ?>
                                    <div class="accordion" id="ac<?= $hash ?>">
                                        <div class="row custom-table">
                                            <div class="col-md-4 border-right">
                                                <span type="span" class="plus-minus collapsed ml-3"
                                                      data-toggle="collapse" data-target="#collaps<?= $hash ?>"><i
                                                            class="fa fa-plus"></i>
                                                    <?= $cam ?>
                                                </span>
                                            </div>
                                            <div class="col-md-1"><?= $campaign['count'] ?></div>
                                            <div class="col-md-1 border-right"><?= $campaign['percent'] ?>%</div>
                                            <div class="col-md-1"><?= $campaign['channel_2']['count'] ?></div>
                                            <div class="col-md-1 border-right"><?= percent($campaign['channel_2']['count'], $campaign['count']) ?>
                                                %
                                            </div>
                                            <div class="col-md-1 "><?= $campaign['channel_3']['count'] ?></div>
                                            <div class="col-md-1"><?= percent($campaign['channel_3']['count'], $campaign['channel_2']['count']) ?>
                                                %
                                            </div>
                                            <div class="col-md-1 border-right"><?= percent($campaign['channel_3']['count'], $campaign['count']) ?>
                                                %
                                            </div>
                                        </div>

                                        <div id="collaps<?= $hash ?>" class="collapse"
                                             aria-labelledby="heading<?= $hash ?>"
                                             data-parent="#ac<?= $hash ?>">
                                            <?php $i = 0; ?>
                                            <?php foreach ($campaign['posts'] as $po => $post): ?>
                                                <?php if ($po != 'Нет подписки'): ?>
                                                    <?php $hash = salt(8) ?>
                                                    <div class="accordion" id="acb<?= $hash ?>">
                                                    <div class="row custom-table">
                                                        <div class="col-md-4 border-right">
                                                        <span type="span" class="plus-minus collapsed ml-4"
                                                              data-toggle="collapse"
                                                              data-target="#collapsb<?= $hash ?>"><i
                                                                    class="fa fa-plus"></i>
                                                            <?= $po ?>
                                                        </span>
                                                        </div>
                                                        <div class="col-md-1"><?= $post['count'] ?></div>
                                                        <div class="col-md-1 border-right"><?= $post['percent'] ?>%
                                                        </div>
                                                        <div class="col-md-1"><?= $post['count'] ?></div>
                                                        <div class="col-md-1 border-right">100%</div>
                                                        <div class="col-md-1"><?= $post['channel_3']['count'] ?></div>
                                                        <div class="col-md-1"><?= percent($post['channel_3']['count'], $post['count']) ?>
                                                            %
                                                        </div>
                                                        <div class="col-md-1 border-right"><?= percent($post['channel_3']['count'], $post['count']) ?>
                                                            %
                                                        </div>
                                                    </div>

                                                    <div id="collapsb<?= $hash ?>" class="collapse"
                                                    aria-labelledby="heading<?= $hash ?>"
                                                    data-parent="#acb<?= $hash ?>">
                                                <?php endif; ?>

                                                <?php $i = 0; ?>
                                                <?php foreach ($post['posts'] as $po2 => $post2): ?>
                                                    <?php $hash = salt(8) ?>
                                                    <div class="accordion" id="acb<?= $hash ?>">
                                                        <div class="row custom-table">
                                                            <div class="col-md-4 border-right">
                                                                    <span type="span" class="plus-minus collapsed ml-5"
                                                                          data-toggle="collapse"
                                                                          data-target="#collapsb<?= $hash ?>"><i
                                                                                class="fa fa-plus"></i>
                                                                        <?= $po2 ?>
                                                                    </span>
                                                            </div>
                                                            <div class="col-md-1"><?= $post2['count'] ?></div>
                                                            <div class="col-md-1 border-right"><?= $post2['percent'] ?>%
                                                            </div>
                                                            <div class="col-md-1"><?= $post2['count'] ?></div>
                                                            <div class="col-md-1 border-right">100%
                                                            </div>
                                                            <div class="col-md-1"><?= $post2['count'] ?></div>
                                                            <div class="col-md-1">100%
                                                            </div>
                                                            <div class="col-md-1 border-right">100%
                                                            </div>
                                                        </div>

                                                        <div id="collapsb<?= $hash ?>" class="collapse"
                                                             aria-labelledby="heading<?= $hash ?>"
                                                             data-parent="#acb<?= $hash ?>">

                                                        </div>
                                                    </div>
                                                    <?php $i++; ?>
                                                <?php endforeach; ?>

                                                <?php if ($po != 'Нет подписки'): ?>
                                                    </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        // Add minus icon for collapse element which is open by default
        // $(".collapse.show").each(function(){
        //     $(this).parent().find(".fa").addClass("fa-minus").removeClass("fa-plus");
        // });

        // Toggle plus minus icon on show hide of collapse element
        $(".plus-minus").click(function () {
            if ($(this).hasClass('collapsed')) {
                $(this).find('.fa').addClass('fa-minus').removeClass('fa-plus')
            } else {
                $(this).find('.fa').addClass('fa-plus').removeClass('fa-minus')
            }
        })
    });
</script>
</body>
</html>
