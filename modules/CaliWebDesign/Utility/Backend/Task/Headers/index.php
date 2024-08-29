    <div class="caliweb-card dashboard-card custom-padding-account-card">

        <?php

            if ($pagetitle == "Tasks" && $pagesubtitle == "Edit") {

        ?>

                <div class="card-header-account">
                    <div class="display-flex align-center">
                        <div class="no-padding margin-10px-right icon-size-formatted">
                            <img src="/assets/img/systemIcons/singletask.png" alt="Client Logo and/or Business Logo" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                        </div>
                        <div>
                            <p class="no-padding font-14px" style="padding-bottom:4px;">Task</p>
                            <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                
                                <?php

                                    echo 'Task ID: '.$taskid.' - '.$manageTaskDefintionK->taskname;
                            
                                ?>
                                <?php

                                    echo '
                                    
                                        <select class="form-input" style="margin-top:0; padding:6px 12px; width:unset; margin-left:20px; border:1px solid #ddd;" name="editStatus" id="editStatus" required="">
                                            <option>Completed</option>
                                            <option>Pending</option>
                                            <option>Stuck</option>
                                            <option>Overdue</option>
                                        </select>

                                    ';

                                ?>
                                    
                            </h4>
                        </div>
                    </div>
                    <div class="display-flex align-center">
                        <button type="submit" name="submit" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Save</button>
                        <a href="/dashboard/administration/tasks/viewTask/?task_id=<?php echo $taskid; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                    </div>
                </div>
                <div class="card-body width-75 macBook-styling-hotfix">
                    <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">
                        <div class="width-75">
                            <p class="no-padding font-14px">Assigned To</p>
                            <p class="no-padding font-14px"><?php echo $manageTaskDefintionK->assigneduser; ?></p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Start Date</p>
                            <p class="no-padding font-14px">

                                <?php
                                    
                                    $formatstartdate = new \DateTime($manageTaskDefintionK->taskstartdate);
                                    $formattedStartDate = $formatstartdate->format('F j, Y g:i A');
                                    echo $formattedStartDate;

                                ?>
                        
                            </p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Due Date</p>
                            <p class="no-padding font-14px">

                                <?php

                                    $formatduedate = new \DateTime($manageTaskDefintionK->taskduedate);
                                    $formattedDueDate = $formatduedate->format('F j, Y g:i A');
                                    echo $formattedDueDate;

                                ?>
                                
                            </p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Priority</p>
                            <select class="form-input" style="margin-top:0; padding:6px 12px; width:unset; border:1px solid #ddd;" name="editPriority" id="editPriority" required="">
                                <option>Highest</option>
                                <option>Elevated</option>
                                <option>Normal</option>
                            </select>
                        </div>
                    </div>
                </div>

        <?php

            } else {

        ?>

                <div class="card-header-account">
                    <div class="display-flex align-center">
                        <div class="no-padding margin-10px-right icon-size-formatted">
                            <img src="/assets/img/systemIcons/singletask.png" alt="Client Logo and/or Business Logo" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                        </div>
                        <div>
                            <p class="no-padding font-14px" style="padding-bottom:4px;">Task</p>
                            <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                
                                <?php

                                    echo 'Task ID: '.$taskid.' - '.$manageTaskDefintionK->taskname;
                            
                                ?>
                                <?php

                                    $statusClasses = [
                                        "Completed" => "green",
                                        "Overdue" => "red",
                                        "Stuck" => "red-dark",
                                        "Pending" => "yellow"
                                    ];
                                    
                                    $statusClass = $statusClasses[ucwords(strtolower($manageTaskDefintionK->status))] ?? 'default';
                                    echo "<span class='account-status-badge $statusClass'>{$manageTaskDefintionK->status}</span>";

                                ?>
                                    
                            </h4>
                        </div>
                    </div>
                    <div class="display-flex align-center">
                        <a href="/dashboard/administration/tasks/editTask/?task_id=<?php echo $taskid; ?>" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
                    </div>
                </div>
                <div class="card-body width-75 macBook-styling-hotfix">
                    <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">
                        <div class="width-75">
                            <p class="no-padding font-14px">Assigned To</p>
                            <p class="no-padding font-14px"><?php echo $manageTaskDefintionK->assigneduser; ?></p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Start Date</p>
                            <p class="no-padding font-14px">

                                <?php
                                    
                                    $formatstartdate = new \DateTime($manageTaskDefintionK->taskstartdate);
                                    $formattedStartDate = $formatstartdate->format('F j, Y g:i A');
                                    echo $formattedStartDate;

                                ?>
                        
                            </p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Due Date</p>
                            <p class="no-padding font-14px">

                                <?php

                                    $formatduedate = new \DateTime($manageTaskDefintionK->taskduedate);
                                    $formattedDueDate = $formatduedate->format('F j, Y g:i A');
                                    echo $formattedDueDate;

                                ?>
                                
                            </p>
                        </div>
                        <div class="width-50">
                            <p class="no-padding font-14px">Priority</p>
                            <p class="no-padding font-14px"><?php echo $manageTaskDefintionK->taskpriority; ?></p>
                        </div>
                    </div>
                </div>

        <?php

            }

        ?>

    </div>