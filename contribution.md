
##These are the general guidlines for anyone who wants to contribute to the repo##

- The folders have their meaning and the files related to the particular groups should be places inside the respective file.
 For eg: 
 `Providers` has a provider folder, commands have commands folder, templates have a templates folder and so on.
- Comments in the code is a must. If the logic is complex, it MUST be explained in the comments.
- I have tried to use single responsibility principle in all the classes but the writer class and file manipulation are mixed up, those need to be separated into another file
- I did this in a hurry so if anyone is willing to use open / closed. I am willing to help. ( code to interface i.e. ).
- The strings to be replaced MUST always be StudlyCase encapsulated by two percentage. for eg: %%MyName%%

Concepts and techniques used in this.
- I have created separate classes for each commands and generator files ( SRP )
- The properties in the generators classes are the ones that we will replace in the template
for eg: `formRequest`, `storeRoute`, `listRoute` in the controller are the variables that I think are the ones that only controller need The variables are directly passed into the template file and replaced there. Feel free to add any other properties / variables that you think will be replaced in the template file
    The variabled to be replaced MUST be a propery of a class and MUST not be randomly replaced.
    
 -
-  Anything that can be / should be replacable should be configurable through the crud config file. 

