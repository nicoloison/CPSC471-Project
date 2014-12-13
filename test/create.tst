curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" -F "prep_time=75" -F "portions=4" -F "description=saucy ribs" -F "image=@/home/liam/src/school/cpsc471/CPSC471-Project/test/Baby-Back-Ribs.jpg" localhost/recipes/create.php
curl localhost/recipes/create.php
curl -F "name=ribs" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" -F "prep_time=75" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" -F "prep_time=75" -F "portions=4" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" -F "prep_time=75" -F "portions=4" -F "description=saucy ribs" -F "image=@/home/liam/src/school/cpsc471/CPSC471-Project/test/Baby-Back-Ribs.jpg" localhost/recipes/create.php
curl -F "name=ribs" -F "author_name=adjuric" -F "instructions=grill em" -F "prep_time=75" -F "portions=4" -F "description=saucy ribs" localhost/recipes/create.php
