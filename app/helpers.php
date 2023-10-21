<?php

function gravatar_url($email)
{
    return 'https://www.gravatar.com/avatar/' . md5($email). '?s=40&d=mm' ;
}