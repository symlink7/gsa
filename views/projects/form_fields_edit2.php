                          <?
                          echo Form::row(
                            Form::label("budget_approved_orig", 
                              "CAO Original Budget"),
                            Form::input_col(
                              Form::budget_box("budget_approved_orig", 
                                $budget_approved_orig)
                            ), false /* no border */
                          );

                          echo Form::row(
                            Form::label("budget_approved_revised", 
                              "Board Approved Revised Budget"),
                            Form::input_col(
                              Form::budget_box("budget_approved_revised", 
                                $budget_approved_revised)
                            )
                          );

                          echo Form::row(
                            Form::label("budget_available", 
                              "Available Budget"),
                            Form::input_col(
                              Form::budget_box("budget_available", 
                                $budget_available)
                            )
                          );
                          ?>
