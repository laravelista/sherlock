{% spaceless %}
{% set currentLevel = 1 %}
{% for chapter in library %}
    {% if loop.first %}
        <ul><li><a href="#{{ chapter.slug }}">{{ chapter.name }}</a>
    {% elseif chapter.level == library[loop.index0 -1]['level']  %}
        </li><li><a href="#{{ chapter.slug }}">{{ chapter.name }}</a>
    {% elseif chapter.level > library[loop.index0 -1]['level']  %}
        {% set currentLevel = currentLevel + 1 %}
        </li><li><ul><li><a href="#{{ chapter.slug }}">{{ chapter.name }}</a>
    {% elseif chapter.level < library[loop.index0 -1]['level']  %}
        {% set currentLevel = currentLevel - 1 %}
        </li></ul><li><a href="#{{ chapter.slug }}">{{ chapter.name }}</a>
    {% endif %}
    {% if loop.last %}
        {% for i in range(1, currentLevel) %}
            </li></ul>
        {% endfor %}
    {% endif %}
{% endfor %}
{% endspaceless %}