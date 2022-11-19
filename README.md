# OpenTHC Star CloudPRNT Server

This is an implementation of a CloudPRNT server in PHP.

See also: https://github.com/star-micronics/cloudprnt-sdk

## CloudPRNT

This service from Star has information scattered across the web.
For example, [Star Micronics USA](https://starmicronics.com/cloudprnt-web-cloud-online-pos-receipt-printing-sdk-developers/) is pretty thin;
but [Star EMEA](https://star-emea.com/products/cloudprnt/) has a nice overview.
However [Star M Japan](https://www.star-m.jp/products/s_print/CloudPRNTSDK/Documentation/en/developerguide/introduction.html) has incredible documentation of the protocol.
Confusing but, it's all different parts of the same global company.

## Installation

```
git clone $REPO /opt/openthc/cloudprnt-server
sudo make install
```

Then configure Apache/Nginx as desired.
