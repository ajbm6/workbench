<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;

use Padosoft\Workbench\Traits\Enumerable;

class MethodHttpHelper
{
    use Enumerable;

    const OPTIONS='OPTIONS';
    const GET='GET';
    const HEAD='HEAD';
    const POST='POST';
    const MULTIPART_POST='MULTIPART POST';
    const PUT='PUT';
    const DELETE='DELETE';
    const TRACE='TRACE';

}