<?php
use function htmlgen\html as h;
use function htmlgen\render;
use function htmlgen\map;
use function htmlgen\raw;

echo
	h('html',
		h('head',
			h('meta',['charset'=>'utf-8']),
			h('title',$title)
		),
		raw($content)
	)
;