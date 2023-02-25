# Twelve ZZZ Top

- [Here's a thought experiment](#Here)
- [What this app does](#What this app does)
- [Minimal running setup](#Minimal running setup)
  - [Prerequisites](#Prerequisites)
  - [Run the app locally](#Run the app locally)
  - [Tests](#Tests)

## Here's a thought experiment

We're in the middle of World War I. To get his mind off things, Leonhardt Wittgenstein, a hardcore pythagorean arithmologist, sets out to solve all solvable problems in bureaucracy once and for all by employing a mere total of 121.023 propositions.

He decides to structure these in a tree-like fashion: first the basic theses, in a specific order; should then any thesis be elaborated by way of sub-theses, the latter should all inhabit a sub-level _under_ the parent thesis. Proceed inductively, that is, should one of the sub-theses be further elaborated, this should be done in a sub-sub-level, and so on and so forth, each level under the same parent being specifically ordered.

He understands very well that it would help his readers to label his propositions in a way that would reflect the structure he had in mind, so, as was customary at the time, he employs a mixed lexicographic enumeratiion: basic theses get numbered by a number written using the normal arabic digits, `1`, `2`, `3`, ..., `12`, et cetera, while _nested_ propositions get numbered by letters of the alphabet and ordered as in the phone-books to come: `12a`, `12b`, `12ba`, `12bb`, `12bc` et cetera. In a streak of foresightedness he even decides against his native alphabet and uses exclusively characters without any diacritics: twenty six latin letters in total.

Now, our Wittgenstein is certainly a clever man, but he's neither an actual mathematician, nor a designer or a typographer; in deciding against diacritics, he sadly also abolishes the dot character, and thus fails to indicate the change from a level to a sub-level -- he rests assured that the lexicographic order should do it. He even feels he has a breakthrough once he realizes that he can elaborate a thesis in more than just twenty six sub-theses (as the number of the latin alphabet would strictly allow), if he just adds an `a` after he's reached a `z` and carries onwards: ..., `12x`, `12y`, `12z`, `12za`, `12zb`, and so on. Ingenious!

Indeed, here's an excerpt of his celebrated magnum opus, the _Tractatus Logicobureaucraticus_:

>[...]
>
>12ny. Commission Implementing Regulation (EU) No 437/1914 of 29 April 1914 approving 4,5-Dichloro-2-octyl-2H-isothiazol-3-one as an existing active substance for use in biocidal products for product-type 21 (OJ L 128, 30.4.1914, p. 64).
>
>12nz. Commission Implementing Regulation (EU) No 438/1914 of 29 April 1914 approving cyproconazole as an existing active substance for use in biocidal products for product-type 8 (OJ L 128, 30.4.1914, p. 68).
>
>12nza. Commission Delegated Regulation (EU) No 1062/1914 of 4 August 1914 on the work programme for the systematic examination of all existing active substances contained in biocidal products referred to in Regulation (EU) No 528/1912 of the European Parliament and of the Council (OJ L 294, 10.10.1914, p. 1), as amended by: [...]
>
>12nzb. Commission Implementing Regulation (EU) No 1090/1914 of 16 October 1914 approving permethrin as an existing active substance for use in biocidal products for product-types 8 and 18 (OJ L 299, 17.10.1914, p. 10).
>
>[...]

A heated controversy among Theoretical Bureaucracy scholars continues to this day over what he meant by the transition from `12nz` to `12nza`; on the one side there are the _incrementalists_, who claim that he meant "`12.14.26` on to `12.14.27`", and on the other side, of course, the _indentationists_, who fervently believe that he meant `12.14.26` into `12.14.26.1`.

Now, wouldn't this explain why Wittgenstein's _Tractatus_ is hailed as one of the most cryptic and also most misunderstood pieces of theory in human history?

Excuse the authors of this app for busying themselves with such an arcane thought experiment. Certainly, in our very advanced, very digital, very practical age of clarity, no such conundrums are really possible.

## What this app does

This piece of software accepts strings that consist of a positive integer head and a tail of latin characters, and no other symbols or spaces; for example, `12zzztop`. It returns all possible nestings that the string may suggest, considering the _ambiguity_ of the character `z`: when it is followed by a character it could mean both an increment and an indentation. The nestings are returned into their lexicographic form, as well as in their arabic and greek numeral representation.

The output of the string above would be
```
![Output for input `12zzztop`](12zzztop.png "sample output")
```

## Minimal running setup

### Prerequisites
- [php 8](https://www.php.net/manual/en/install.php)
- [composer](https://getcomposer.org/download/)
- php-xml: `sudo apt install php-xml`
- phpunit: `sudo apt install phpunit`

### Run the app locally

- Run `composer install` to install project dependencies.
- Run `php -S 127.0.0.1:8000` to start the local php server.
- Open the browser at http://127.0.0.1:8000/index.php.

### Tests

- Run the tests by `./vendor/bin/phpunit tests`.

(Disambiguate a string that starts with an integer and possibly continues with latin characters, based on a specified-as-ambiguous character.)
