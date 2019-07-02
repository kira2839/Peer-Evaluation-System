#!/bin/bash

php -r "include('../backend/get_score.php'); GetScore::getInstance()->getPerCourse();"
