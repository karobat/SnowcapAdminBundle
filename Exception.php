<?php
namespace Snowcap\AdminBundle;

class Exception extends \ErrorException {
    const ADMIN_INVALID = 10;
    const ADMIN_UNKNOWN = 20;

    const LIST_INVALID = 110;
    const LIST_NOQUERYBUILDER = 120;
    const LIST_INVALIDQUERYBUILDER = 130;
}