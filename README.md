## Profile do Drupal padrão usado na FFLCH


## mandatory

A pasta *mandatory* foi criada para forçar que algumas configurações
voltem aos valores defaults do nosso profile, para isso, há links na pasta mandatory
apontando para alguns arquivos .yml da pasta install.
Assim, mesmo que usuários tenham alterado essas configurações, pode-se rodar o seguinte
comando para obrigar o site a voltar para estado do momento da instalação:

    cd your-drupal-site
    ./vendor/bin/drush cim --partial --source='profiles/contrib/drupal-profile-fflch/config/mandatory'

### Configurações do editor:

 - Somente o full_html está disponível
 - Botão com atríbutos do link usando módulo editor_advanced_link
 - Botão de arquivo usando o módulo editor_file
 - Tamanho e tipo de fonte usando editor_font

Segurança

 - somente administradores podem criar novas contas
 - role fflch pode alterar nome do site

Módulos disponíveis na role fflch:

 - Google Analytics
 - Assets

Formato de datas disponíveis:

 - dia/mes/ano: d/m/Y
 - extenso: l, j \d\e F \d\e Y

Gestão de conteúdo

 - página básica (com url baseada no título)
 - clone dos nodes
 - conditional fields
 - webform
 - blocos
 - menus
 - views

Temas disponíveis:

 - fflch_aegan
 - fflch_jethro
 - fflch_nexus
 - fflch_paxton
