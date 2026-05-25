import os

ctrl_path = 'app/Http/Controllers/TableOrderController.php'
with open(ctrl_path, 'r') as f:
    ctrl_content = f.read()

ctrl_content = ctrl_content.replace(
    "if ($model->JenisPaket === 'PAKETMEMBER' && empty($model->paketid)) {\n                $model->paketid = -1;\n            }",
    "if ($model->JenisPaket === 'PAKETMEMBER') {\n                $model->paketid = -1;\n            }"
)

with open(ctrl_path, 'w') as f:
    f.write(ctrl_content)
