# Profile Drupal usado na FFLCH

## Adicionando novas configurações

Há dois tipos de configurações: instalação e sincronização.
As configurações de instalação são carregadas assim que o site é criado
e estão definidas em arquivos *.yml* no diretório *fflchprofile/config/install*.

As configurações de sinconização são rodadas sempre no cron e estão
em *modules/fflch_configs/config/mandatory*.

Passos para fazer modificações no site modelo:

 - Identificar os arquivos yml que executam a modificação
 - Salvar e commitar esses arquivos na pasta *modules/fflch_configs/config/mandatory*

 - Se quiser testar as configurações antes de mandar para a produção, coloque-a em um pasta,
por exemplo, /tmp/novas/.yml e rode:

    ./vendor/bin/drush cim --partial --source='/tmp/novas'

## Configurações:

### Editor de texto:

 - Somente o full_html está disponível
 - Botão com atríbutos do link usando módulo editor_advanced_link
 - Botão de arquivo usando o módulo editor_file
 - Tamanho e tipo de fonte usando editor_font

### Segurança

 - somente administradores podem criar novas contas
 - role fflch pode alterar nome do site

### Módulos disponíveis na role fflch:

 - Google Analytics
 - Assets

### Formato de datas disponíveis:

 - dia/mes/ano: d/m/Y
 - extenso: l, j \d\e F \d\e Y

### Gestão de conteúdo

 - página básica (com url baseada no título)
 - clone dos nodes
 - conditional fields
 - webform
 - blocos
 - menus
 - views

