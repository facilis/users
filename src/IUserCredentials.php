<?php

namespace Facilis\Users;

interface IUserCredentials
{
    function getUsername();



    function isPasswordValid($password);
}