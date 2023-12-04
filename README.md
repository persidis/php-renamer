# php-renamer
Searches all pdf files it finds in folder "/putFilesHere" for a specific keyword and renames the file with a word that follows. Usefull if you want to mass rename pdf files (like automatically produced invoices) with a term found in the pdf e.g. Surname.

Uses pdfparser (https://github.com/smalot/pdfparser) to parse the pdf file. Because pdfparser isn't always accurate you can tryout some of the options given as to which term to use after the search word.

Also creates a csv log file with the new filenames.
