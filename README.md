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

## TODO:

Formatação de Texto

 - Habilitar módulo book.
 - Em tipo de conteúdo, apagar “book page”.
 - Habilitar página básica para ser transformada em livro: admin/structure/book/settings

Segurança

 - somente administradores criarem novas contas
 - (TODO) role fflch pode alterar nome do site
 - (TODO) captcha image em todos forms: 

Módulos disponíveis na role fflch:

 - Google Analytics
 - Assets

Formato de datas disponíveis:

 - dia/mes/ano: d/m/Y
 - extenso: l, j \d\e F \d\e Y

Gestão de conteúdo

 - Clone dos nodes
 - página básica
 - (TODO) conditional fields
 - Url baseada no título
 - webform
 - blocos
 - menus
 - views
 - (TODO) gestão de imagens com IMCE

Temas disponíveis:

 - fflch_aegan
 - fflch_jethro
 - fflch_nexus
 - fflch_paxton


