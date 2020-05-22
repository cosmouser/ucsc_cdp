# UCSC Profiles
A plugin for Wordpress that adds a Gutenberg block and a shortcode for adding dynamic profile sections.

## Example shortcode
`[ucsc_profiles cruzids="cosmo, sammy" title=true phone=true email=true]`

`[ucsc_profiles displaystyle=list]`
## Shortcode attributes guide
| Attribute          | Default Value | Options                |
|--------------------|---------------|------------------------|
| cruzids            | cosmo         | "cosmo, sammy, etc"    |
| photo              | true          | true \| false          |
| name               | true          | true \| false          |
| title              | false         | true \| false          |
| phone              | false         | true \| false          |
| email              | false         | true \| false          |
| websites           | false         | true \| false          |
| officelocation     | false         | true \| false          |
| officehours        | false         | true \| false          |
| expertise          | false         | true \| short \| false |
| biography          | false         | true \| short \| false |
| areas_of_expertise | false         | true \| false          |
| research_interests | false         | true \| short \| false |
| teaching_interests | false         | true \| short \| false |
| awards             | false         | true \| short \| false |
| publications       | false         | true \| short \| false |
| profilelinks       | true          | true \| false          |
| displaystyle       | grid          | grid \| list           |
