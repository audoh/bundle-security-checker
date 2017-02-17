# SecurityCheckerBundle
SecurityCheckerBundle for Symfony and Twig lets you check whether the current user could access a given route based on that route's @Security annotation (it does not work for any other methods).

You could use this to, for example, only show a page link to a user who can actually access that page, without repeating your security definitions in the Twig template.