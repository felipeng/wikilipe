[toc]

# Syntax

The syntax is basically the same of [MarkDown](https://github.com/showdownjs/showdown/wiki/Showdown's-Markdown-syntax) with a little changes.

## Headers

It is possible to use until 6 sub topics.
Alternatively, it is possible to use: `#`, `=` or `_` characters.

Example:
```ini
# Topic 1
## Topic 2
### Topic 3
#### Topic 4
##### Topic 5
###### Topic 6
```

# Topic 1
## Topic 2
### Topic 3
#### Topic 4
##### Topic 5
###### Topic 6

## Lists

### Unordered list

Alternatively, it is possible to use: `*`, `-` or `+` characters.

```ini
* Fruit
    * Apple
        * Apple-pear
    * Grape
* Vegetables
     * Lettuce
     * Chicory
```

* Fruit
    * Apple
        * Apple-pear
    * Grape
* Vegetables
     * Lettuce
     * Chicory

### Ordered List

```ini
1. Item 1
    1. Sub-item 1
        1. Sub-sub-item 1
    1. Sub-item 2
1. Item 2
```

1. Item 1
    1. Sub-item 1
        1. Sub-sub-item 1
    1. Sub-item 1
1. Item 2

## Blockquotes

```ini
> Blockquotes are very handy in email to emulate reply text.
> This line is part of the same quote.
```

> Blockquotes are very handy in email to emulate reply text.
> This line is part of the same quote.


## Emphasis

Alternatively, it is possible to use: `*` or `_` characters.

* \*italic* = *italic*
* \**bold** = **bold**
* \*\*\*bold and italic*** = ***bold and italic***
* \~\~crossed out\~\~ = ~~crossed out~~

## Automatic Recognition

* Email: `user@domain.com` = user@domain.com
* URL: `http://www.google.com` = http://www.google.com

## Links

### External Links
`[external link](http://www.google.com)` = [external link](http://www.google.com)

### Internal Link

`[internal page](?p=dir/subdir/page)` = [internal page](?p=dir/subdir/page)


### Images

It is not mandatory to type the extensions

`![Text](data/imgs/wikilipe "Optional Text")`
![Text](data/imgs/wikilipe "Optional Text")

Resize:
`![Alt text](data/imgs/wikilipe =250x80 "Optional title")`
![Alt text](data/imgs/wikilipe =250x80 "Optional title")

Size:
`![Alt text](data/imgs/wikilipe =100x* "Optional title")`
![Alt text](data/imgs/wikilipe =150x* "Optional title")

## Table of Contents

Table of Contents (ToC) it is an extension [showdownjs extensions (showdown-toc)](https://github.com/JanLoebel/showdown-toc). It is automatically generated, just insert the `[ toc ]` inside the page.

## Source code

Inline \`code\` has back-ticks around it: `code`

Block of code use with 3 back-ticks: \`\`\` at the beginning and the end.

\`\`\`bash
 #!/bin/bash
VAR="teste"
echo $VAR
\`\`\`

```bash
#!/bin/bash
VAR="teste"
echo $VAR
```

For syntax highlighting it uses a javascript plug-ing called [highlightjs](http://highlightjs.org)

## Horizontal Rule

Is defined by: `---`, `***` or `___`.

---

## Escaping Characters

Just insert \ before it. Example: not horizontal rule: `\---`

## HTML Code

To insert HTML code, just insert the HTML code :^)

```html
<div style='border: 1px dashed red; padding: 5px; width: 150px; text-align: center'>
<span style='background-color: black;  color: white; padding: 2px'>Test HTML code</span>
</div>
```

<div style='border: 1px dashed red; padding: 5px; width: 150px; text-align: center'>
<span style='background-color: black;  color: white; padding: 2px'>Test HTML code</span>
</div>

## Tables

```
| Left       | Center       | Right       |
| -----------|:------------:| -----------:|
|  item1     | item2        | item3       |
|  item1     | item2        | item3       |
```
| Left       | Center       | Right       |
| -----------|:------------:| -----------:|
|  item1     | item2        | item3       |
|  item1     | item2        | item3       |

## Tasklist

It is possible to create a task list:
```
 - [x] This task is done
 - [ ] This is still pending
```

 - [x] This task is done
 - [ ] This is still pending
