<?php
$custom_buttons = Array(
						Array('label'=>'跟踪记录','title'=>'添加跟踪记录',
							'command'=>"account.add_track_popup(recordid,'AccountTrack','Accounts')"
						),
                        Array('label'=>'去年保险记录','title'=>'去年保险记录',
                            'command'=>"account.set(recordid,'Insurance')"
                        ),
						/*
						 Array('label'=>'理赔查询','title'=>'理赔查询',//只有江苏使用，此按钮默认屏蔽
					        'command'=>"account.jiangsu_insurance(recordid)"
					    ),*/
					);
$tpl_file="Accounts/DetailView.tpl";
require_once(_ROOT_DIR.'/common/detailView.php');
?>